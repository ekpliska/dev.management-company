<?php

    namespace app\models;
    use yii\base\Model;
    use app\models\User;
    use Yii;
    use app\models\SmsOperations;
    
/*
 * Сброс пароля
 */    
class PasswordResetRequestForm extends Model {
    
    public $phone;
    public $sms_code;
    
    public function rules() {
        return [
            
            [['phone', 'sms_code'], 'required'],
            
            ['sms_code', 'string', 'min' => 5, 'max' => 5],
            
        ];
    }
    
    /*
     * Валидация введенного СМС-кода
     * Проверка на уникальность введенного номера телефона
     */
    public function afterValidate() {
        
        if (!$this->hasErrors()) {
            
            $sms_code = isset(Yii::$app->session['reset_sms_code']) ? Yii::$app->session['reset_sms_code'] : null;
            $user = User::findByPhone($this->phone);

            if ($sms_code != $this->sms_code) {
                $this->addError('sms_code', 'Вы указали не верный СМС код');
                return false;
            }
            
            if (empty($user)) {
                $this->addError('phone', 'Указанный номер телефона не зарегистрирован');
                return false;
            }
            
        }
        
        parent::afterValidate();        
    }
    
    /*
     * Поиск пользователя по указанному номеру телефона
     * Отправляем ему новый временный пароль на номер телефона
     */
    public function resetPassword() {
        
        if (!$this->validate()) {
            return false;
        }
        
        $user = User::findByPhone($this->phone);
        
        if (empty($user)) {
            return false;
        }
        
        // Генерируем новый пароль (случайные 6 символов)
        $new_password = Yii::$app->security->generateRandomString(6);
        
        if (!$this->sendSms($user->user_mobile, $user->user_login, $new_password)) {
            return false;
        }
        
        // Хешируем новый пароль
        $user->user_password = Yii::$app->security->generatePasswordHash($new_password);
        $user->save();
        
        Yii::$app->session->destroy();
            
        return true;
    }
    
    /*
     * Отправка СМС-кода на номер мобильного телефона
     */
    public function sendSms($_phone, $user_login, $new_password) {
        
        $phone = preg_replace('/[^0-9]/', '', $_phone);
        
        $sms = Yii::$app->sms;
        
        $result = $sms->send_sms($phone,
                'Для вашей учетной записи был создан новый пароль. Используйте его при следующем входе в личный кабинет. '
                . 'Напоминаем, ваш логин: ' . $user_login . ', ваш новый пароль: ' . $new_password);
        
        if (!$sms->isSuccess($result)) {
//            return $sms->getError($result);
            return false;
        }
        return true;
        
    }
    
    public function attributeLabels() {
        
        return [
            'phone' => 'Мобильный телефон',
            'sms_code' => 'СМС код',
        ];
        
    }
    
}
