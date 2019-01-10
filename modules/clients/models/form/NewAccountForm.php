<?php

    namespace app\modules\clients\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\PersonalAccount;
    use app\models\Houses;
    use app\models\Flats;
    use app\models\Counters;
    use app\models\TypeCounters;
    use app\models\ReadingCounters;

/**
 * Создание нового лицевого счета
 */
class NewAccountForm extends Model {
    
    public $account_number;
    public $last_sum;
    public $square;
    
    public $client_info = [];


    public function rules() {
        
        return [
            [['account_number', 'last_sum', 'square'], 'required'],
//            ['account_number', 'string', 'min' => 12, 'max' => 12],
            
            [['square'], 'double', 'min' => 20.00, 'max' => 1000.00, 'message' => 'Площадь жилого помещения указана не верно. Пример, 80.27'],
            
            ['last_sum', 'double', 'min' => 00.00, 'max' => 100000.00, 'message' => 'Сумма предыдущей квитанции указана не верно. Пример: 2578.70'],
            
            ['account_number', 'checkPersonalAccount'],
            
        ];
    }
    
    /*
     * Валидация номера лицевого счета
     * Проверка существования лицевого счета в БД
     * Проверка актуализации лицевого счета по API
     */
    public function checkPersonalAccount() {
        
        $account = $this->account_number;
        $summ = $this->last_sum;
        $square = $this->square;
        
        // Формируем запрос в массиве
        $array_request = [
            'Номер лицевого счета' => $account,
            'Сумма последней квитанции' => $summ,
            'Площадь жилого помещения' => $square,
        ];
        // Преобразуем массив в JSON запрос на отправку по API
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
                    
        // Отправляем запрос по API        
        $result_api = Yii::$app->client_api->accountRegister($data_json);
        
        // Проверяем текущую базу на существование лицевого счета
        $is_account = PersonalAccount::findAccountBeforeRegister($account);
        
        if ($is_account == true) {
            $errorMsg = 'Указанный лицевой счет используется';
            $this->addError('account_number', $errorMsg);            
        } elseif ($is_account == false && $result_api['success'] == false) {
            $this->addError('account_number', 'Регистрационные данные лицевого счета введены некорректно');            
        }
        
//        if ($is_account == false && ($result_api['success'] == 'error')) {
//            $this->addError('account_number', 'Регистрационные данные лицевого счета введены некорректно');
//            return false;
//        }
        
        $this->client_info = $result_api['account_info'];
        
        return true;
        
    }    
    
    /*
     * В качестве параметра передаем ID текущего лицевого счета
     * У текущего лицевого счета убираем статус "Текущий"
     */
    public function createAccount($old_account_id) {
                
        if (!$this->validate()) {
            return false;
        }
                
        $house_info = $this->client_info;
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $house = new Houses();
            $house_data_api = $house_info['Жилая площадь'];
            $house_id = $house::isExistence(
                    $house_data_api['House adress'], 
                    $house_data_api['Полный адрес Собственника'],
                    $house_data_api['Номер дома']);

            $flat = new Flats();
            $flat->flats_house_id = $house_id;
            $flat->flats_porch = $house_data_api['Номер подъезда'];
            $flat->flats_floor = $house_data_api['Номер этажа'];
            $flat->flats_number = $house_data_api['Номер квартиры'];
            $flat->flats_rooms = $house_data_api['Количество комнат'];
            $flat->flats_square = $this->square;

            if (!$flat->save()) {
                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $flat->getFirstErrors()));
            }

            $account = new PersonalAccount();
            $account->account_number = $this->account_number;
            $account->account_organization_id = 1;
            $account->account_balance = $house_info['Лицевой счет']['Баланс'];
            $account->personal_clients_id = Yii::$app->userProfile->clientID;
            $account->personal_rent_id = null;
            $account->personal_flat_id = $flat->flats_id;

            if (!$account->save()) {
                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $account->getFirstErrors()));
            }
            
            $account::changeCurrentAccount($old_account_id, $account->account_id);
            
            $transaction->commit();
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        
        return true;
    }
    
    /*
     * Проверяем наличие существования дома, квартиры для нового лицевого счета
     * 
     * Проверяем наличие пришедшего из запроса по API дома,
     *      если дом не найден и квартира на найдена в БД - то, создаем новую записи в БД (дом, кватира, ЛС)
     * 
     * Проверяем наличие пришедшего из запроса по API дома,
     *      если дом найден и квартира на найдена в БД - то, создаем новую записи в БД (кватира, ЛС)
     * 
     */
    private function checkHouse($house_info, $old_account_id) {
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $house = new Houses();
            $house_data_api = $house_info['Жилая площадь'];
            $house_id = $house::isExistence(
                    $house_data_api['House adress'], 
                    $house_data_api['Полный адрес Собственника'],
                    $house_data_api['Номер дома']);

            $flat = new Flats();
            $flat->flats_house_id = $house_id;
            $flat->flats_porch = $house_data_api['Номер подъезда'];
            $flat->flats_floor = $house_data_api['Номер этажа'];
            $flat->flats_number = $house_data_api['Номер квартиры'];
            $flat->flats_rooms = $house_data_api['Количество комнат'];
            $flat->flats_square = $this->square;

            if (!$flat->save()) {
                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $flat->getFirstErrors()));
            }

            $account = new PersonalAccount();
            $account->account_number = $this->account_number;
            $account->account_organization_id = 1;
            $account->account_balance = $house_info['Лицевой счет']['Баланс'];
            $account->personal_clients_id = Yii::$app->userProfile->clientID;
            $account->personal_rent_id = null;
            $account->personal_flat_id = $flat->flats_id;

            if (!$account->save()) {
                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $account->getFirstErrors()));
            }
            
            $account::changeCurrentAccount($old_account_id, $account->account_id);
            
            $transaction->commit();
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }        
        
    }
    
//    /*
//     * Создание нового Лицевого счета, с закрепленным домом и квартирой
//     */
//    private function createHouseAndFlat($data) {
//        
//        $transaction = Yii::$app->db->beginTransaction();
//
//        try {
//            $house = new Houses();
//            $house->houses_postcode = $data['Жилая площадь']['Индекс'];
//            $house->houses_region = $data['Жилая площадь']['Область'];
//            $house->houses_area = $data['Жилая площадь']['Район'];
//            $house->houses_town = $data['Жилая площадь']['Город'];
//            $house->houses_village = $data['Жилая площадь']['Поселок'] ? $data['Жилая площадь']['Поселок'] : null;
//            $house->houses_street = $data['Жилая площадь']['Улица'];
//            $house->houses_number_house = $data['Жилая площадь']['Номер дома'];
//            
//            if (!$house->save()) {
//                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $house->getFirstErrors()));
//            }
//            
//            $flat = new Flats();
//            $flat->flats_house_id = $house->houses_id;
//            $flat->flats_porch = $data['Жилая площадь']['Номер подъезда'];
//            $flat->flats_floor = $data['Жилая площадь']['Номер этажа'];
//            $flat->flats_number = $data['Жилая площадь']['Номер квартиры'];
//            $flat->flats_rooms = $data['Жилая площадь']['Количество комнат'];
//            $flat->flats_square = $this->square;
//
//            if (!$flat->save()) {
//                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $flat->getFirstErrors()));
//            }
//            
//            $account = new PersonalAccount();
//            $account->account_number = $this->account_number;
//            $account->account_organization_id = 1;
//            $account->account_balance = $data['Лицевой счет']['Баланс'];
//            $account->personal_clients_id = Yii::$app->userProfile->clientID;
//            $account->personal_rent_id = null;
//            $account->personal_flat_id = $flat->flats_id;
//
//            if (!$account->save()) {
//                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $account->getFirstErrors()));
//            }
//            
//            $transaction->commit();
//            
//        } catch (Exception $ex) {
//            $transaction->rollBack();
//        }
//        
//    }
//
//    /*
//     * Создание нового Лицевого счета, с закрепленным домом и квартирой
//     */    
//    private function createFlat($houses_id, $data) {
//        
//        $transaction = Yii::$app->db->beginTransaction();
//
//        try {
//            
//            $flat = new Flats();
//            $flat->flats_house_id = $houses_id;
//            $flat->flats_porch = $data['Жилая площадь']['Номер подъезда'];
//            $flat->flats_floor = $data['Жилая площадь']['Номер этажа'];
//            $flat->flats_number = $data['Жилая площадь']['Номер квартиры'];
//            $flat->flats_rooms = $data['Жилая площадь']['Количество комнат'];
//            $flat->flats_square = $this->square;
//
//            if (!$flat->save()) {
//                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $flat->getFirstErrors()));
//            }
//            
//            $account = new PersonalAccount();
//            $account->account_number = trim($this->account_number, '№');
//            $account->account_organization_id = 1;
//            $account->account_balance = $data['Лицевой счет']['Баланс'];
//            $account->personal_clients_id = Yii::$app->userProfile->clientID;
//            $account->personal_rent_id = null;
//            $account->personal_flat_id = $flat->flats_id;
//
//            if (!$account->save()) {
//                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $account->getFirstErrors()));
//            }
//            
//            $this->setCookies($account->account_id);
//            $this->setCountersInfo($account->account_id, $data);
//            
//            $transaction->commit();
//            
//        } catch (Exception $ex) {
//            $transaction->rollBack();
//        }
//        
//    }
    
//    /*
//     * После создания нового лицевого счета, передаем его в куки как текущий
//     */
//    private function setCurrentAccount($account_id) {
//        
//        Yii::$app->response->cookies->add(new \yii\web\Cookie([
//            'name' => '_userAccount',
//            'value' => $account_id,
//            'expire' => time() + 60*60*24*7,
//        ]));
//        
//        return true;
//    }
    
//    /*
//     * Добавление приборов учета в БД
//     */
//    private function setCountersInfo($account_id, $data) {
//        
//        $counters_info = $data['Приборы учета'];
//        
//        $type_counters = TypeCounters::getTypeCountersLists();
//        
//        if (is_array($counters_info)) {
//            foreach ($counters_info as $key => $counter) {
//                $counter = new Counters();
//                $counter->counters_type_id = TypeCounters::getTypeID($counters_info[$key]['Тип прибора учета']);
//                $counter->counters_number = $counters_info[$key]['Регистрационный номер прибора учета'];
//                $counter->date_check = strtotime($counters_info[$key]['Дата следующей поверки']);
//                $counter->counters_description = null;
//                $counter->counters_account_id = $account_id;
//                $counter->isActive = Counters::STATUS_ACTIVE;
//                if (!$counter->save()) {
//                    return false;
//                }
//                /*
//                 *  Получение показаний приборов учета
//                 *  Хранить в нашей БД показания не будем
//                 *  (17-12-2018)
//                 */
//                // $this->setCounterReadings($counter->counters_id, $counters_info[$key]);
//                
//            }
//            return true;
//        }
//    }
//    
//    /*
//     * Запись показаний приборов учета Собственника
//     */
//    private function setCounterReadings($counter_id, $data) {
//        
//        $previous_reading = new ReadingCounters();
//        $previous_reading->reading_counter_id = $counter_id;
//        $previous_reading->readings_indication = $data['Предыдущие показание'];
//        $previous_reading->date_reading = strtotime($data['Дата снятия показания']);
//        $previous_reading->user_id = Yii::$app->user->identity->id;
//        
//        if (!$previous_reading->save()) {
//            return false;
//        }
//        
//        if ($data['Текущее показание'] != null) {
//            $current_reading = new ReadingCounters();
//            $current_reading->reading_counter_id = $counter_id;
//            $current_reading->readings_indication = $data['Текущее показание'];
//            $current_reading->date_reading = time();
//            $current_reading->user_id = Yii::$app->user->identity->id;
//            if (!$current_reading->save()) {
//                return false;
//            }
//        }
//        
//        return true;
//
//    }
    
    public function attributeLabels() {
        
        return [
            'account_number' => 'Номер лицевого счета',
            'last_sum' => 'Сумма предыдущей квитанции',
            'square' => 'Площадь квартиры, указанной в квитанции',
        ];
        
    }
    
}
