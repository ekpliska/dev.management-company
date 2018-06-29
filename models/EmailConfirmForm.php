<?php
    namespace app\models;
    use yii\base\Model;
    use yii\base\InvalidParamException;
    use app\models\User;

class EmailConfirmForm extends Model {
    
    public $_user;

    public function __construct($token, $config = []) {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Отсутствует код подтверждения.');
        }
        $this->_user = User::findByEmailConfirmToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Неверный токен.');
        }
        parent::__construct($config);
    }
 
    public function confirmEmail() {
        $user = $this->_user;
        $user->status = User::STATUS_ENABLED;
        $user->email_confirm_token = null;        
        return $user->save();
    }
    
}
?>