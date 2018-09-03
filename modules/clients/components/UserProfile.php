<?php

    namespace app\modules\clients\components;
    use yii\base\BaseObject;
    use yii\base\InvalidConfigException;
    use Yii;
    use app\models\User;

/* 
 * Профиль пользователя
 * 
 * Данный компоненнт предназвачен для получения полной информации о текущем пользователе
 */

class UserProfile extends BaseObject {
    
    public $_user;
    public $_user_id;

    public function init() {
        $this->_user_id = Yii::$app->user->identity->id;
        if (User::findByUser($this->_user_id) == null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        return $this->info();
    }
    
    public function info() {
        
        if ($this->_user_id == null) {
            throw new InvalidConfigException('Ошибка в передаче аргументов. При вызове компонента не был задан ID пользователя');
        }
        
        if (Yii::$app->user->can('clients')) {
            $info = (new \yii\db\Query)
                    ->select('c.clients_id as client_id, c.clients_name as name, c.clients_second_name as second_name, c.clients_surname as surname, '
                        . 'c.clients_mobile as mobile, c.clients_phone as phone, '
                        . 'u.user_id as user_id, u.user_login as login, '
                        . 'u.user_email as email, u.user_photo as photo, '
                        . 'u.created_at as date_created , u.last_login as last_login, '
                        . 'u.status, '
                        . 'pa.account_number as account')
                    ->from('user as u')
                    ->join('LEFT JOIN', 'clients as c', 'u.user_client_id = c.clients_id')
                    ->join('LEFT JOIN', 'personal_account as pa', 'u.user_client_id = pa.personal_clients_id')
                    ->where(['u.user_id' => $this->_user_id])
                    ->one();
            
            return $this->_user = $info;
            
        };
        
        if (Yii::$app->user->can('clients_rent')) {
            $info = (new \yii\db\Query)
                    ->select('r.rents_id as client_id, r.rents_name as name, r.rents_second_name as second_name, r.rents_surname as surname, '
                        . 'r.rents_mobile as mobile, r.rents_mobile_more as phone, '
                        . 'u.user_id as user_id, u.user_login as login, '
                        . 'u.user_email as email, u.user_photo as photo, '
                        . 'u.created_at as date_created , u.last_login as last_login, '
                        . 'u.status, '
                        . 'pa.account_number as account')
                    ->from('user as u')
                    ->join('LEFT JOIN', 'rents as r', 'u.user_rent_id = r.rents_id')
                    ->join('LEFT JOIN', 'personal_account as pa', 'u.user_rent_id = pa.personal_rent_id')
                    ->where(['u.user_id' => $this->_user_id])
                    ->one();
            
            return $this->_user = $info;
        }
        
    }
    
    /*
     * Метод получения информации о текущем пользователе
     */
    public function getUserInfo() {
        
        return $this->_user['email'];
        
    }
    
    /*
     * ID пользователя
     */
    public function getUserID() {
        return $this->_user['user_id'];
    }
    
    /*
     * Имя пользователя
     */
    public function getUsername() {
        return $this->_user['login'];
    }
    
    /*
     * Электронная почта пользоватедя
     */
    public function getEmail() {
        return $this->_user['email'];
    }
    
    /*
     * Аватар пользователя
     */
    public function getPhoto() {
        if (!empty($this->_user['photo'])) {
            return Yii::getAlias('@web') . '/images/no-avatar.jpg';
        }
        return Yii::getAlias('@web') . $this->_user['photo'];
    }
    
    /*
     * Дата регистрации
     */
    public function getDateRegister() {
        return $this->_user['date_created'];
    }
    
    /*
     * Дата последнего логина
     */
    public function getLastLogin() {
        if (empty($this->_user['last_login'])) {
            return time();
        }
        return $this->_user['last_login'];
    }
    
    /*
     * Статус учетной записи пользователя
     */
    public function getStatus($status) {
        return User::className()->getUserStatus($status);
    }
    
    /*
     * Получить роль пользователя
     */
    public function getRole() {
        if (Yii::$app->user->can('clients')) {
            return Yii::$app->authManager->getRole('clients')->description;
        }
        return Yii::$app->authManager->getRole('clients_rent')->description;
    }
    
    /*
     * ID Собственника/Арендатора
     */
    public function getClientID() {
        return $this->_user['client_id'];
    }
    
    /*
     * Фамилия имя отчество Собственника/Аренедтора
     * Формат: Фамилия И.О.
     */
    public function getFullNameClient() {
        $_name = mb_substr($this->_user['name'], 0, 1, 'UTF-8');
        $_second_name = mb_substr($this->_user['second_name'], 0, 1, 'UTF-8');
        return $this->_user['surname'] . ' ' . $_name . '. ' . $_second_name . '.';
    }
    
    /*
     * Мобальный телефон Собственника/Аренедтора
     */
    public function getMobile() {
        return $this->_user['mobile'];
    }
    
    /*
     * Дополнительный телефон Собственника/Аренедтора
     */
    public function getOtherPhone() {
        return $this->_user['phone'];
    }
    
    /*
     * Лицевой счет Собственника/Аренедтора
     */
    public function getAccount() {
        return $this->_user['account'];
    }
    
}
