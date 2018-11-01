<?php

    namespace app\models\signup;
    use Yii;
    use yii\base\Model;
    use app\models\PersonalAccount;

/**
 * Регистрация, шаг второй
 */
class SignupStepThree extends Model {
    
    public $phone;
    public $sms_code;
    
    public function rules() {
        return [
            [['phone', 'sms_code'], 'required'],
        ];
    }
    
    public function afterValidate() {
        
        if (!$this->hasErrors()) {
            
            $sms_code = Yii::$app->session->get('sms_code');

            if ($sms_code != $this->sms_code) {
                $this->addError($attribute, 'Вы указали не верный СМС код');
                return false;
            }
        }
        
        parent::afterValidate();
        
    }
    
    public function attributeLabels() {
        return [
            'phone' => 'Номер телефонв',
            'sms_code' => 'СМС код',
        ];
    }
    
    
    
}
