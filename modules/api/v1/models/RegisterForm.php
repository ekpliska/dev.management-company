<?php

    namespace app\modules\api\v1\models;
    use Yii;
    use yii\base\Model;
    use app\models\PersonalAccount;
    use app\models\User;
    use app\models\Clients;
    use app\models\Houses;
    use app\models\Flats;
    use app\models\Token;
    use app\models\SmsSettings;

/**
 * Регистрация по API
 */
class RegisterForm extends Model {

    /*
     * Первый шаг регистрации,
     * Проверяем актуальность лицевого счета на стороне заказчика,
     * Запоминает данные прищедшие по API
     */
    public static function registerStepOne($data) {

        if (!array_key_exists('account_number', $data) || 
                !array_key_exists('last_summ', $data) || !array_key_exists('square', $data)) {
            return [
                'success' => false,
                'message' => 'Ошибка передачи данных',
            ];
        }
        
        $data_array = [
            "Номер лицевого счета" => $data['account_number'],
            "Сумма последней квитанции" => $data['last_summ'],
            "Площадь жилого помещения" => $data['square'],
        ];
        // Преобразуем массив в json
        $data_json = json_encode($data_array, JSON_UNESCAPED_UNICODE);
        // Отправляем запрос по API        
        $result_api = Yii::$app->client_api->accountRegister($data_json);
        // Проверяем текущую базу на существование лицевого счета
        $is_account = PersonalAccount::findAccountBeforeRegister($data['account_number']);

        if ($is_account == true) {
            return [
                'success' => false,
                'message' => 'Указанный номер номер лицевого счета зарегистрирован',
            ];
        }

        if ($is_account == false && $result_api['Лицевой счет']['success'] == false) {
            return [
                'success' => false,
                'message' => 'Регистрационные данные лицевого счета введены некорректно',
            ];
        }
        
        Yii::$app->session->set('result_api', $result_api);
        Yii::$app->session->set('account_number', $data['account_number']);
        Yii::$app->session->set('square', $data['square']);
        
        return ['success' => true];
    }
    
    /*
     * Второй шаг регистрации,
     * Проверка уникальности введенного электронного адреса
     */
    public static function registerStepTwo($data) {
        
        if (!array_key_exists('email', $data)) {
            return [
                'success' => false,
                'message' => 'Ошибка передачи данных',
            ];
        }
        
        if (User::findByEmail($data['email'])) {
            return [
                'success' => false,
                'message' => 'Электронный адрес в системе зарегистрирован',
            ];
        }
        
        Yii::$app->session->set('email', $data['email']);
        Yii::$app->session->set('password', $data['password']);
        
        return ['success' => true];
        
    }
    
    /*
     * Третий шаг регистрации,
     * Проверка уникальности введенного мобильного телефона
     */
    public static function registerStepThree($data) {
        
        if (!array_key_exists('phone', $data)) {
            return [
                'success' => false,
                'message' => 'Ошибка передачи данных',
            ];
        }
        
        if (User::findByPhone($data['phone'])) {
            return [
                'success' => false,
                'message' => 'Номер мобильного телефона в системе зарегистрирован',
            ];
        }
        
        Yii::$app->session->set('phone', $data['phone']);
        
        // Формируем случайный смс-код
        $sms_code = mt_rand(10000, 99999);
        
        $phone = preg_replace('/[^0-9]/', '', $data['phone']);
        
        $sms_code = mt_rand(10000, 99999);
        // Отправляем смс на указанный номер телефона
        if (!$result = Yii::$app->sms->generalMethodSendSms(SmsSettings::TYPE_NOTICE_REGISTER, $phone, $sms_code)) {
            return ['success' => false, 'message' => $result];
        }
        
        return [
            'success' => true,
            'sms_code' => (string)$sms_code,
        ];
        
    }
    
    /*
     * Завершающий шаг регистрации,
     * Сохраняем данные в БД
     */
    public static function registerStepFinish() {
        
        $client_data_api = Yii::$app->session->get('result_api')['Собственник'];
        $account_number = Yii::$app->session->get('account_number');
        $square = Yii::$app->session->get('square');
        $house_data_api = Yii::$app->session->get('result_api')['Жилая площадь'];
        $email = Yii::$app->session->get('email');
        $password = Yii::$app->session->get('password');
        $phone = Yii::$app->session->get('phone');

        if (!$client_data_api || !$account_number || !$square || !$house_data_api || 
                !$email || !$password || !$phone) {
            
            return [
                'success' => false,
                'message' => 'Ошибка регистрации',
            ];
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            // Сохраняем данные Собственника
            $client = new Clients();
            // Данные Собственника, пришедщие по API
            $client->clients_surname = $client_data_api['Фамилия'] ? preg_replace('/[^a-zа-яё\ \d]/ui', '', $client_data_api['Фамилия']) : 'Не задано';
            $client->clients_name = $client_data_api['Имя'] ? preg_replace('/[^a-zа-яё\ \d]/ui', '', $client_data_api['Имя']) : 'Не задано';
            $client->clients_second_name = $client_data_api['Отчество'] ? preg_replace('/[^a-zа-яё\ \d]/ui', '', $client_data_api['Отчество']) : 'Не задано';
            $client->clients_phone = $client_data_api['Домашний телефон'] ? preg_replace('/[^a-zа-яё\ \d]/ui', '', $client_data_api['Домашний телефон']) : '';
            
            if (!$client->save()) {
                return ['success' => false];
                
            }
            
            // Создаем нового пользователя
            $user = new User();
            $user->user_login = $account_number;
            $user->user_password = Yii::$app->security->generatePasswordHash($password);
            $user->user_email = $email;
            $user->user_mobile = $phone;
            $user->user_client_id = $client->clients_id;
            // Новый пользователь получает статус доступа в систему
            $user->status = User::STATUS_ENABLED;
            // По умолчанию включаем email оповещение
            $user->user_check_email = true;
            
            if (!$user->save()) {
                Yii::$app->session->destroy();
                return ['success' => false];
            }
            
            // Назначение роли пользователю
            $user_role = Yii::$app->authManager->getRole('clients');
            Yii::$app->authManager->assign($user_role, $user->user_id);
            
            // Дом
            $house = new Houses();
            $house_id = $house::isExistence(
                            $house_data_api['house_id'], 
                            isset($house_data_api['house_name']) ? $house_data_api['house_name'] : 'Жилой комплекс', 
                            $house_data_api['Полный адрес Собственника'],
                            $house_data_api['Улица'],
                            $house_data_api['Номер дома']);
        
            // Квартира
            $flat = new Flats();
            $flat->flats_house_id = $house_id;
            $flat->flats_porch = $house_data_api['Номер подъезда'];
            $flat->flats_floor = $house_data_api['Номер этажа'];
            $flat->flats_number = $house_data_api['Номер квартиры'];
            $flat->flats_rooms = $house_data_api['Количество комнат'];
            $flat->flats_square = $square;
            if (!$flat->save()) {
                Yii::$app->session->destroy();
                return ['success' => false];
            }
        
            // Лицевой счет
            $account = new PersonalAccount();
            $account->account_number = $account_number;
            $account->account_organization_id = 1;
            $account->personal_clients_id = $client->clients_id;
            $account->account_balance = Yii::$app->session->get('result_api')['Лицевой счет']['Баланс'];
            $account->personal_flat_id = $flat->flats_id;
            // Устанавливаем зарегистрированных лицевой счет как текущий
            $account->isActive = PersonalAccount::STATUS_CURRENT;
            if (!$account->save()) {
                Yii::$app->session->destroy();
                return ['success' => false];
            }
            
            // Для зарегистрированного пользователя формируем токен, для автологина
            $token = new Token();
            $token->user_uid = $user->user_id;
            $token->generateToken(time() + 3600 * 24 * 365);
            $token->save(false);
            
            // Дропаем сессию в случае успешной регистрации нового пользователя
            Yii::$app->session->destroy();

            $transaction->commit();
            
            $client = Clients::findOne(['clients_id' => $user->user_client_id]);
            
            return [
                'success' => true,
                'token' => $token->token,
                'expired_at' => $token->expired_at,
                'user_photo' => $user->getPhoto(),
                'user_fullname' => $client->fullName,
                'role' => 'clients',
            ];
            
        } catch (Exception $e) {
            Yii::$app->session->destroy();
            $transaction->rollBack();
            return false;
        }
        
    }
        
}
