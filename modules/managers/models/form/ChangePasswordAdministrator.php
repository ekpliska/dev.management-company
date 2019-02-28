<?php

    namespace app\modules\managers\models\form;
    use yii\base\Model;
    use app\models\User;

/**
 *  Смена пароля пользователя со стороны Администратора
 */
class ChangePasswordAdministrator extends Model {
    
    public $new_password;
    public $new_password_repeat;

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
            [['new_password', 'new_password_repeat'], 'required', 'message' => 'Вы не заполнили поля'],

            [['new_password', 'new_password_repeat'], 
                'match', 
                'pattern' => '/^[A-Za-z0-9\_\-]+$/iu', 
                'message' => 'Пароль может содержать только буквы английского алфавита, цифры, - и _',
            ],            
            
            [['new_password', 'new_password_repeat'], 'string', 'min' => 6, 'max' => 12],
            ['new_password', 'compare', 'compareAttribute' => 'new_password_repeat', 'message' => 'Указанные пароли не совпадают'],
        ];
    }
    
    /*
     * Метода смены пароля пользователя
     */
    public function changePassword() {
        
        if ($this->validate()) {
            $user = $this->_user;
            $user->setUserPassword($this->new_password);
            return $user->save();
        } else {
            return false;
        }
        
    }
    
    /*
     * Метки полей
     */
    public function attributeLabels() {
        
        return [
            'new_password' => 'Новый пароль',
            'new_password_repeat' => 'Пароль еще раз',
        ];
    }
    
}
