<?php
    namespace app\models;
    use yii\base\Model;
    use yii\base\InvalidParamException;
    use app\models\User;
    use Yii;

/*
 * Подтверждение регистрации пользователя по ссылке присланной в письме
 * Ссылка содержит случайно сгенерированный ключ/токен
 */    
class EmailConfirmForm extends Model {
    
    public $_user;

    /*
     * При каждом объявлении класса проверяем наличие токена
     * При наличии токена осуществляем поиск пользователя по заданному токену
     * 
     */
    public function __construct($token, $config = []) {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Отсутствует код подтверждения');
        }
        $this->_user = User::findByEmailConfirmToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Неверный код подтверждения');
        }
        parent::__construct($config);
    }
 
    /*
     * Метод, который меняет статус пользователя на активный;
     * Удаляет из таблицы Пользователи токен, сформированный для подтверждение регистрации;
     * Назначет роль для зарегистрированного пользователя, Клиент
     */
    public function confirmEmail() {
        $user = $this->_user;
        
        $user->status = User::STATUS_ENABLED;
        $user->email_confirm_token = null;
        
        // Назначение роли пользователю
        $userRole = Yii::$app->authManager->getRole('clients');
        Yii::$app->authManager->assign($userRole, $user->id);
        
        return $user->save(false);
    }
    
}
?>