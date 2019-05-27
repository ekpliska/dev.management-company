<?php

    namespace app\models\signup;
    use yii\base\Model;

/**
 * Регистрация, шаг второй
 */
class SignupStepTwo extends Model {
    
    public $email;
    public $password;
    public $password_repeat;
    
    public function rules() {
        return [
            [['email'], 'required', 'message' => 'Введите корректный e-mail'],
            [['password', 'password_repeat'], 'required', 'message' => 'Требуется ввести пароль'],
            
            ['email', 'email'],
            
            [['password', 'password_repeat'], 'string', 'min' => 6, 'max' => 12],
            
            ['password_repeat', 
                'compare', 
                'compareAttribute' => 'password', 'message' => 'Указанные пароли не совпадают!'],
            
            [['password', 'password_repeat'],
                'match', 
                'pattern' => '/^[A-Za-z0-9\\_\\-]+$/iu', 
                'message' => 'Вы используете не допустимые символы в пароле',
            ],
            
        ];
    }
    
    public function afterValidate() {
        
        if (!$this->hasErrors()) {
            
            $email = $this->email;

            $is_user = \app\models\User::findByEmail($email);

            if ($is_user != null) {
                $this->addError('email', 'Указанный электронный адрес используется в системе');
                return false;
            }
        }
        
        parent::afterValidate();
        
    }
    
    public function attributeLabels() {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
            'password_repeat' => 'Пароль еще раз',
        ];
    }
    
    
    
}
