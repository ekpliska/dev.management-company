<?php

    namespace app\modules\api\models;
    use Yii;
    use yii\base\Model;
    use app\models\Token;
    use app\models\User;
    
/*
 * Модель авторизации по API
 */
class LoginForm extends Model {

    public $username;
    public $password;
    
    private $_user;
    
    /*
     * Правила валидации
     */
    public function rules() {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }
    
    /*
     * Валидация введенного пароля
     */
    public function validatePassword($attribute, $params) {
        
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверно указан логин или пароль');
            }
        }
    }
    
    /*
     * Метод аутентификации пользователя
     */
    public function auth() {
        
        if ($this->validate()) {
            $token = new Token();
            $token->user_uid = $this->getUser()->id;
            $token->generateToken(time() + 3600 * 24);
            return $token->save() ? $token : null;
        } else {
            return null;
        }
    }
    
    protected function getUser() {
        
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
    
}
