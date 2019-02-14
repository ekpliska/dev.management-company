<?php

    namespace app\modules\dispatchers\components;
    use yii\base\BaseObject;
    use yii\base\InvalidConfigException;
    use Yii;
    use yii\helpers\ArrayHelper;
    use app\models\User;

/* 
 * Профиль пользователя
 * 
 * Данный компоненнт предназвачен для получения полной информации о текущем пользователе
 */

class ProfileDispatcher extends BaseObject {
    
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
        
        if (Yii::$app->user->can('administrator')) {
            $info = (new \yii\db\Query)
                    ->select('e.employee_id as employee_id, '
                        . 'e.employee_name as name, e.employee_second_name as second_name, e.employee_surname as surname, '
                        . 'd.department_name as department_name, '
                        . 'u.user_id as user_id, u.user_login as login, '
                        . 'u.user_email as email, u.user_photo as photo, '
                        . 'u.user_mobile as mobile, '
                        . 'u.created_at as date_created , u.last_login as last_login, '
                        . 'u.status as status')
                    ->from('user as u')
                    ->join('LEFT JOIN', 'employees as e', 'u.user_employee_id = e.employee_id')
                    ->join('LEFT JOIN', 'departments as d', 'e.employee_department_id = d.department_id')
                    ->where(['u.user_id' => $this->_user_id])
                    ->one();
            
            var_dump($info); die();
            
            return $this->_user = $info;
            
        };        
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
        return ArrayHelper::getValue(User::arrayUserStatus(), $this->_user['status']);
    }
    
    /*
     * Получить роль пользователя
     */
    public function getRole() {
        if (Yii::$app->user->can('administrator')) {
            return Yii::$app->authManager->getRole('administrator')->description;
        } elseif (Yii::$app->user->can('dispatcher')) {
            return Yii::$app->authManager->getRole('dispatcher')->description;
        } elseif (Yii::$app->user->can('specialist')) {
            return Yii::$app->authManager->getRole('specialist')->description;
        }
        return 'Роль ползователя не определена';
    }
    
    /*
     * ID Собственника/Арендатора
     */
    public function getEmployeeID() {
        return $this->_user['employee_id'];
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
     * Фамилия имя отчество Сотрудника
     * Формат: Фамилия И.О.
     */
    public function getFullNameEmployee() {
        $_name = mb_substr($this->_user['name'], 0, 1, 'UTF-8');
        $_second_name = mb_substr($this->_user['second_name'], 0, 1, 'UTF-8');
        return $this->_user['surname'] . ' ' . $_name . '. ' . $_second_name . '.';
    }
    
    /*
     * Мобильный телефон Сотрудника
     */
    public function getMobile() {
        return $this->_user['mobile'];
    }

    public function getDepartment() {
        return $this->_user['department_name'];
    }
        
}
