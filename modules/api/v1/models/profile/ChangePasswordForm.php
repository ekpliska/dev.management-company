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
            [['old_password', 'new_password'], 'string', 'min' => 6, 'max' => 12],
            ['old_password', 'checkOldPassword'],
        ];
        
    }
    
    public function checkOldPassword($attribute, $param) {
        
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Текущий и введенный пароли не совпадают');
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
