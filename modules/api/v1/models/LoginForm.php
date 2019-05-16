<?php

    namespace app\modules\api\v1\models;
    use Yii;
    use yii\base\Model;
    use app\models\Token;
    use app\models\User;
    use app\models\Clients;
    use app\models\Rents;
    
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
            } elseif ($user && $user->status == User::STATUS_BLOCK) {
                $this->addError($attribute, 'Ваша учетная запись заблокирована');
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
        $rent = Rents::findOne(['rents_id' => $this->getUser()->user_rent_id]);
        $role = $this->getRole($this->getUser()->id);
        
        $response = [
            'user_uid' => $token->user_uid,
            'token' => $token->token,
            'expired_at' => $token->expired_at,
            'user_photo' => $this->getUser()->getPhoto(),
            'user_fullname' => $role == 'clients' ? $client->fullName : $rent->fullName,
            'role' => $role,
        ];
        
        return $response;
        
    }
    
    /*
     * Получить роль пользователя
     */
    private function getRole($user_id) {
        $role = (new \yii\db\Query())
                ->select(['item_name'])
                ->from('auth_assignment')
                ->andWhere(['=', 'user_id', $user_id])
                ->one();
        
        return $role['item_name'];
    }
    
    protected function getUser() {
        
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
    
}
