<?php

    namespace app\modules\clients\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\SmsOperations;
    use app\models\User;

/**
 *  Смена пароля пользователя, ввод смс кода
 */
class SMSForm extends Model {
    
    public $sms_code;
    private $_user;
    
    public function __construct(User $user, $config = []) {
        
        $this->_user = $user;
        parent::__construct($config);
        
    }    
    
    /*
     * Правила валидации
     */
    public function rules() {
        return [
            ['sms_code', 'required'],
            ['sms_code', 'checkSMSCode'],
        ];
    }
    
    /*
     * Проверка валидации введенного смс кода и существующим в бд
     */
    public function checkSMSCode() {
        
        $record = SmsOperations::findBySMSCode($this->sms_code);
        
        if ($record == null) {
            $this->addError('sms_code', 'Введённый код неверен');
            return true;
        }
        
        // Если время запроса на смену пароля истекло, удаляем куку и запись на смену пароля
        if (Yii::$app->request->cookies->has('_time')) {
            $record->delete(false);
            $this->addError('sms_code', 'Время действия кода истекло');
            return true;
        }
        
    }
    
    /*
     * Организация смены пароля пользователя
     * Если валидация введенного смс кода успешна, то куку по смс операции удаляем
     */
    public function changePassword() {
        
        $record = SmsOperations::findBySMSCode($this->sms_code);
        
        if ($this->validate()) {
            $user = $this->_user;
            $user->user_password = $record->value;
            $user->save();
            $record->delete(false);
            Yii::$app->response->cookies->remove('_time');
            return true;

        }
        return false;
    }
    
    public function attributeLabels() {
        return [
            'sms_code' => 'СМС код'
        ];
    }
    
}
