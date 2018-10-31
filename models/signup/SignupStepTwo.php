<?php

    namespace app\models\signup;
    use yii\base\Model;
    use app\models\PersonalAccount;

/**
 * Регистрация, шаг второй
 */
class SignupStepTwo extends Model {
    
    public $email;
    public $password;
    public $password_repeat;
    
    public function rules() {
        return [
            [['email', 'password', 'password_repeat'], 'required'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
            'password_repeat' => 'Пароль еще раз',
        ];
    }
    
    
    
}
