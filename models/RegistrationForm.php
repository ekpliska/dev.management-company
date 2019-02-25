<?php

    namespace app\models;
    use yii\base\Model;
    use Yii;
    use app\models\PersonalAccount;
    use app\models\Clients;
    use yii\helpers\Html;
    use app\models\Counters;
    

/**
 * Форма регистрации пользователя
 */
class RegistrationForm extends Model {

    public $username;
    public $password;
    public $password_repeat;
    public $last_sum;
    public $square;
    public $mobile_phone;
    public $email;
    
    /*
     * Правила валидации
     */
    public function rules() {
        return [
            [[
                'username', 'password',
                'last_sum', 'square',
                'mobile_phone', 'email',
                'password_repeat'], 'required'
            ],
            
            [[
                'username', 
                'last_sum', 'square',
                'mobile_phone', 'email'], 'filter', 'filter' => 'trim'],
            
            [['username'], 'string', 'min' => 11, 'max' => 11],
            
            // Проверка введенного логина на уникалность в таблице Пользователи
            [
                'username', 'unique', 
                'targetClass' => User::className(), 
                'targetAttribute' => 'user_login', 
                'message' => 'Пользователь с введенным лицевым счетом в системе уже зарегистрирован'
            ],
            
            // Проверка введенного email на уникальность
            [
                'email', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Пользователь с введенным элетронным аресом в системе уже зарегистрирован'
            ],
            
            // Проверка введенного номера телефона на уникальность
            [
                'mobile_phone', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Пользователь с введенным номером мобильного телефона в системе уже зарегистрирован',
            ],
            
            ['mobile_phone', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i'],
            
            ['password', 'compare', 'message' => 'Указанные пароли не совпадают!'],
            [['password_repeat'], 'string', 'min' => 6, 'max' => 12],
            
            ['email', 'email'],
            
        ];
    }
        
    /*
     * Метод описывает первый шаг рагистрации пользователя
     */
    public function registration($data) {
        
//        echo '<pre>'; var_dump($data); die();
        
        if ($data == null) {
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            // Сохраняем данные Собственника
            $client = new Clients();
            // Данные Собственника, пришедщие по API
            $client_data_api = $data['user_info']['Собственник'];
            $client->clients_surname = $client_data_api['Фамилия'] ? $client_data_api['Фамилия'] : 'Не задано';
            $client->clients_name = $client_data_api['Имя'] ? $client_data_api['Имя'] : 'Не задано';
            $client->clients_second_name = $client_data_api['Отчество'] ? $client_data_api['Отчество'] : 'Не задано';
            $client->clients_phone = $client_data_api['Домашний телефон'] ? $client_data_api['Домашний телефон'] : 'Не задано';
            
            if (!$client->save()) {
                // throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $client->getFirstErrors()));
                Yii::$app->session->destroy();
                return false;
            }
            
            // Создаем нового пользователя
            $user = new User();
            $user->user_login = $data['account'];
            $user->user_password = Yii::$app->security->generatePasswordHash($data['password']);
            $user->user_email = $data['email'];
            $user->user_mobile = $data['phone'];
            $user->user_client_id = $client->clients_id;
            // Новый пользователь получает статус доступа в систему
            $user->status = User::STATUS_ENABLED;
            // По умолчанию включаем email оповещение
            $user->user_check_email = true;
            
            if (!$user->save()) {
                // throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $user->getFirstErrors()));
                Yii::$app->session->destroy();
                return false;
            }
            
            // Назначение роли пользователю
            $user_role = Yii::$app->authManager->getRole('clients');
            Yii::$app->authManager->assign($user_role, $user->user_id);
            
            // Дом
            $house = new Houses();
            $house_data_api = $data['user_info']['Жилая площадь'];
            $house_id = $house::isExistence(
                            $house_data_api['House adress'], 
                            $house_data_api['Полный адрес Собственника'],
                            $house_data_api['Номер дома']);
        
            // Квартира
            $flat = new Flats();
            $flat->flats_house_id = $house_id;
            $flat->flats_porch = $house_data_api['Номер подъезда'];
            $flat->flats_floor = $house_data_api['Номер этажа'];
            $flat->flats_number = $house_data_api['Номер квартиры'];
            $flat->flats_rooms = $house_data_api['Количество комнат'];
            $flat->flats_square = $data['square'];
            if (!$flat->save()) {
                // throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $flat->getFirstErrors()));
                Yii::$app->session->destroy();
                return false;
            }
        
            // Лицевой счет
            $account = new PersonalAccount();
            $account->account_number = $data['account'];
            $account->account_organization_id = 1;
            $account->personal_clients_id = $client->clients_id;
            $account->account_balance = $data['user_info']['Лицевой счет']['Баланс'];
            $account->personal_flat_id = $flat->flats_id;
            // Устанавливаем зарегистрированных лицевой счет как текущий
            $account->isActive = PersonalAccount::STATUS_CURRENT;
            if (!$account->save()) {
                // throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $account->getFirstErrors()));
                Yii::$app->session->destroy();
                return false;
            }
            
            // Отправляем регистрационные данные на электронную почту пользователя
            // $this->sendEmail('EmailConfirm', 'Подтверждение регистрации', ['user' => $model]);
            
            $transaction->commit();
            // Дропаем сессию в случае успешной регистрации нового пользователя
            Yii::$app->session->destroy();
            
            return true;
            
            
        } catch (Exception $e) {
            Yii::$app->session->destroy();
            $transaction->rollBack();
            // echo $e->getTraceAsString();
            return false;
        }
        
    }
    
    /*
     * Отправка на электронный адрес ссылки на поддтверждение регистрации
     */
    public function sendEmail($view, $subject, $params = []) {
        $message = Yii::$app->mailer->compose(['html' => 'views/' . $view], $params)
                ->setFrom('email-confirm@site.com')
                ->setTo($this->email)
                ->setSubject($subject)
                ->send();
        return $message;
    }
    
    
    /*
     * Метки для полей формы
     */
    public function attributeLabels() {
        return [
            'username' => 'Номер лицевого счета',
            'last_sum' => 'Сумма предыдущей квитации',
            'square' => 'Площадь квартиры',
            'mobile_phone' => 'Мобильный телефон',
            'email' => 'Электронная почта',
            'password_repeat' => 'Пароль',
            'password' => 'Повторите введенный пароль',
        ];
    }

}
