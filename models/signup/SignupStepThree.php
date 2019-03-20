<?php

    namespace app\models\signup;
    use Yii;
    use yii\base\Model;
    use app\models\User;

/**
 * Регистрация, шаг второй
 */
class SignupStepThree extends Model {
    
    public $phone;
    public $sms_code;
    
    public function rules() {
        return [
            [['phone'], 'required', 'message' => 'Введите номер мобильного телефона'],
            [['sms_code'], 'required', 'message' => 'Введите СМС код'],
            
            ['sms_code', 'string', 'min' => 5, 'max' => 5]
        ];
    }
    
    /*
     * Валидация введенного СМС-кода
     * Проверка на уникальность введенного номера телефона
     */
    public function afterValidate() {
        
        if (!$this->hasErrors()) {
            
            $sms_code = Yii::$app->session->get('sms_code');

            if ($sms_code != $this->sms_code) {
                $this->addError('phone', 'Вы указали не верный СМС код');
                return false;
            }
            
            $phone = $this->phone;
            $is_user = User::findByPhone($phone);

            if ($is_user != null) {
                $this->addError('phone', 'Указанный номер телефона используется в системе');
                return false;
            }
            
        }
        
        parent::afterValidate();        
    }
    
    public function attributeLabels() {
        return [
            'phone' => 'Номер телефона',
            'sms_code' => 'СМС код',
        ];
    }
    
}
