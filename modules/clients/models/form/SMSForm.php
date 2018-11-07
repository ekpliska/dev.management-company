<?php

    namespace app\modules\clients\models\form;
    use yii\base\Model;

/**
 *  Смена пароля пользователя, ввод смс кода
 */
class SMSForm extends Model {
    
    public $sms_code;
    
    public function rules() {
        return [
            ['sms_code', 'required'],
            ['sms_code', 'checkSMSCode'],
        ];
    }
    
    public function checkSMSCode() {
        
//        if (!$this->hasErrors()) {
//            if (!$this->_user->validatePassword($this->$attribute)) {
//                $this->addError($attribute, 'Текущий и введенный пароли не совпадают');
//            }
//        }
        
    }
    
    public function attributeLabels() {
        return [
            'sms_code' => 'СМС код'
        ];
    }
    
}
