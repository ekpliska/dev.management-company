<?php

    namespace app\modules\clients\components;
    use yii\base\BaseObject;
    use yii\base\InvalidConfigException;
    use Yii;
    use yii\helpers\ArrayHelper;
    use app\models\User;
    use app\models\PersonalAccount;

/* 
 * Профиль пользователя
 * 
 * Данный компоненнт предназвачен для получения полной информации о текущем пользователе
 */

class UserProfile extends BaseObject {
    
    public $_user;
    public $_user_id;
    public $_model;

    public function init() {
        
        $this->_user_id = Yii::$app->user->identity->id;
        $this->_model = User::findByUser($this->_user_id);
        
        if ($this->_model == null) {
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
                        . 'c.clients_phone as phone, '
                        . 'u.user_id as user_id, u.user_login as login, '
                        . 'u.user_mobile as user_mobile, '
                        . 'u.user_email as email, u.user_photo as photo, '
                        . 'u.created_at as date_created , u.last_login as last_login, '
                        . 'u.status as status, '
                        . 'pa.account_number as account,'
                        . 'pa.account_balance as balance,'
                        . 'f.flats_id as flat_id,'
                        . 'f.flats_id as flat_id,'
                        . 'h.houses_id as house_id')
                    ->from('user as u')
                    ->join('LEFT JOIN', 'clients as c', 'u.user_client_id = c.clients_id')
                    ->join('LEFT JOIN', 'personal_account as pa', 'u.user_client_id = pa.personal_clients_id')
                    ->join('LEFT JOIN', 'flats as f', 'f.flats_id = pa.personal_flat_id')
                    ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                    ->where(['u.user_id' => $this->_user_id])
                    ->one();
            
            return $this->_user = $info;
            
        };
        
        if (Yii::$app->user->can('clients_rent')) {
            $info = (new \yii\db\Query)
                    ->select('r.rents_id as client_id, r.rents_name as name, r.rents_second_name as second_name, r.rents_surname as surname, '
                        . 'r.rents_mobile_more as phone, '
                        . 'u.user_id as user_id, u.user_login as login, '
                        . 'u.user_mobile as user_mobile, '
                        . 'u.user_email as email, u.user_photo as photo, '
                        . 'u.created_at as date_created , u.last_login as last_login, '
                        . 'u.status as status, '
                        . 'pa.account_number as account, '
                        . 'pa.account_balance as balance')
                    ->from('user as u')
                    ->join('LEFT JOIN', 'rents as r', 'u.user_rent_id = r.rents_id')
                    ->join('LEFT JOIN', 'personal_account as pa', 'u.user_rent_id = pa.personal_rent_id')
                    ->join('LEFT JOIN', 'flats as f', 'f.flats_id = pa.personal_flat_id')
                    ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
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
        if (empty($this->_user['photo'])) {
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
    public function getStatus() {
        
        $status_value = $this->_user['status'];
        $status_name = ArrayHelper::getValue(User::arrayUserStatus(), $this->_user['status']);
        
        return [
            'value' => $status_value,
            'name' => $status_name,
        ];
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
     * Имя
     */
    public function getName() {
        return $this->_user['name'];
    }

    /*
     * Фамилия
     */
    public function getSurname() {
        return $this->_user['surname'];
    }    

    /*
     * Фамилия
     */
    public function getSecondName() {
        return $this->_user['second_name'];
    }
    
    /*
     * Баланс текущего лицевого счета
     */
    public function getBalance() {
        return $this->_user['balance'];
    }
    
    /*
     * Фамилия имя отчество Собственника/Аренедтора
     * Формат: Фамилия Имя Отчество $full = false
     * Формат: Фамилия И.О. $full = true
     */
    public function getFullNameClient($full = true, $off_secondname = false) {
        
        $_name = $full ? $this->_user['name'] . ' ' : mb_substr($this->_user['name'], 0, 1, 'UTF-8') . '. ';
        $_second_name = $full ? $this->_user['second_name'] : mb_substr($this->_user['second_name'], 0, 1, 'UTF-8') . '. ';
        return $this->_user['surname'] . ' ' . $_name . $_second_name;
    }
    
    /*
     * Мобильный телефон Собственника/Арендатор
     */
    public function getMobile() {
        return $this->_user['user_mobile'];
    }
    
    /*
     * Дополнительный телефон Собственника/Арендатор
     */
    public function getOtherPhone() {
        return $this->_user['phone'];
    }
    
    /*
     * Получить ID Дома по текущему лицевому счету
     */
    public function getLivingSpace($account_id) {
        
        $info = PersonalAccount::find()
                ->select(['account_id', 'houses_id', 'personal_flat_id'])
                ->joinWith(['flat', 'flat.house'])
                ->where(['account_id' => $account_id])
                ->asArray()
                ->one();
        
        return $info;
    }
    
    /* Получить полный адрес пользователя
     * по текущему лицевому счету
     */
    public function getFullAdress($account_id) {
        
        $info = PersonalAccount::find()
                ->select(['account_id', 'houses_gis_adress', 'houses_street', 'houses_number', 'flats_number', 'personal_flat_id'])
                ->joinWith(['flat', 'flat.house'])
                ->where(['account_id' => $account_id])
                ->asArray()
                ->one();
        
        $adress_string = $info['houses_gis_adress'] . ', ул. ' . $info['houses_street'] . ', д. ' . $info['houses_number'] . ', кв. ' . $info['flats_number'];
        
        return $info ? $adress_string : 'Адрес не определен';
    }
    

}
