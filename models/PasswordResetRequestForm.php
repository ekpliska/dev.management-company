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
     * Поиск пользователя по указанному номеру телефона
     * Отправляем ему новый временный пароль на номер телефона
     */
    public function resetPassword() {
        
        $user = User::findByEmail($this->mobile_phone);
        if (!$user) {
            return false;
        }
        
        $phone = '';
        
        // Генерируем новый пароль (случайные 6 символов)
        $new_password = Yii::$app->security->generateRandomString(6);
        
        if ($this->sendSms($phone, $new_password)) {
            return false;
        }
        
        // Хешируем новый пароль
        $user->user_password = Yii::$app->security->generatePasswordHash($new_password);
            
        if ($user->save()) {
            $this->sendEmail('ResetPassword', 'Восстановление пароля', ['new_password' => $new_password]);
        }
        return true;
    }
    
    /*
     * Отправка письма с новым паролем
     */
    public function sendEmail($view, $subject, $params = []) {
        $message = Yii::$app->mailer->compose(['html' => 'views/' . $view], $params)
                ->setFrom('email-confirm@site.com')
                ->setTo($this->email)
                ->setSubject($subject)
                ->send();
        return $message;
    }
        
    /*
     * Отправка СМС-кода на номер мобильного телефона
     */
    public function sendSms($phone, $sms_code) {
        
        $sms = Yii::$app->sms;
        
        $result = $sms->send_sms($phone,
                'Вы запросили восстановление пароля на портале "ELSA". Ваш СМС-код ' . $sms_code);
        
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
