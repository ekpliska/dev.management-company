<?php

    namespace app\modules\clients\models\form;
    use yii\base\Model;
    use app\models\RegistrationInVoting;

class CheckSMSVotingForm extends Model {
    
    public $number1;
    public $number2;
    public $number3;
    public $number4;
    public $number5;
    
    public $_code;


    public function __construct($sms_code) {
        parent::__construct();
        return $this->_code = $sms_code;
    }
    
    public function rules() {
        return [
            [['number1', 'number2', 'number3', 'number4', 'number5'], 'required'],
            [['number1', 'number2', 'number3', 'number4', 'number5'], 'string', 'min' => 1, 'max' => 1],
        ];
    }
    
    public function afterValidate() {
        
        $full_code = $this->number1 . $this->number2 . $this->number3 . $this->number4 . $this->number5;
        if (empty($full_code) || $full_code != $this->_code) {
            $this->addError('number1', 'Вы указали неверный СМС код');
        }
        
    }
    
    public function checkSmsCode($voting_id) {
        
        if (!$this->validate()) {
            return false;
        }
        
        // Регистрируем пользователя как участника
        if (!RegistrationInVoting::registrationUser($voting_id)) {
            return false;
        }
        
        return true;
    }
}
