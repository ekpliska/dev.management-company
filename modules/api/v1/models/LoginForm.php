<?php

    namespace app\modules\api\v1\models;
    use Yii;
    use yii\base\Model;
    use app\models\Token;
    use app\models\User;
    use app\models\Clients;
    
/*
 * Модель авторизации по API
 */
class LoginForm extends Model {

    public $username;
    public $password;
    // Токен мобильного устройства, для расслки push-уведомлений
    public $push_token;

    private $_user;
    
    /*
     * Правила валидации
     */
    public function rules() {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
            ['push_token', 'string', 'max' => 255],
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
        
        if (!$this->validate()) {
            return false;
        }
        
        $token = new Token();
        $token->user_uid = $this->getUser()->id;
        $token->generateToken(time() + 3600 * 24 * 365);
        if (!$token->save()) {
            return false;
        }
        
        $client = Clients::findOne(['clients_id' => $this->getUser()->user_client_id]);
        
        $response = [
            'user_uid' => $token->user_uid,
            'token' => $token->token,
            'expired_at' => $token->expired_at,
            'user_photo' => $this->getUser()->getPhoto(),
            'user_fullname' => $client->fullName,
        ];
        
        return $response;
        
    }
    
    protected function getUser() {
        
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
    
}
