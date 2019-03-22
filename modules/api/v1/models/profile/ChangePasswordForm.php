<?php

    namespace app\modules\api\v1\models\profile;
    use Yii;
    use yii\base\Model;
    use app\models\User;

/*
 * Смена пароля
 */
class ChangePasswordForm extends Model {
    
    public $old_password;
    public $new_password;
    
    private $_user;


    public function __construct(User $user, $config = []) {
        
        $this->_user = $user;
        parent::__construct($config);
        
    }
    
    public function rules() {
        
        return [
            [['old_password', 'new_password'], 'required'],
            [['new_password'],
                'match', 
                'pattern' => '/^[A-Za-z0-9\\_\\-]+$/iu', 
                'message' => 'Вы используете запрещенные символы',
            ],
            ['old_password', 'checkOldPassword'],
        ];
        
    }
    
    public function checkOldPassword($attribute, $param) {
        
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Старый пароль указан не верно');
            }
        }
        
    }
    
    public function changePassword() {
        
        if ($this->validate()) {
            $user = $this->_user;
            $user->setUserPassword($this->new_password);
            return $user->save();
        } else {
            return false;
        }
        
    }
    
    
    
    
}
