<?php

    namespace app\modules\clients\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\PersonalAccount;
    use app\models\Houses;
    use app\models\Flats;
    use app\models\Counters;

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
            ['account_number', 'string', 'min' => 11, 'max' => 11],
            
            ['account_number', 'checkPersonalAccount'],
            
//            ['last_sum', 
//                'match', 
//                'pattern' => '/^[0-9]{1,12}(\.[0-9]{0,4})?+$/iu', 
//                'message' => 'Сумма последней квитанции указана не верно. Пример: 2578.70'],
//            
//            ['square', 'match', 'pattern'=>'/^[0-9]{1,12}(\.[0-9]{0,4})?+$/iu', 'message' => 'Площадь жилого помещения указана не верно. Пример, 80.27'],
            
            
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
            
        // Формируем JSON запрос на отправку по API
        $data = "{
                'Номер лицевого счета': '{$account}',
                'Сумма последней квитанции': '{$summ}',
                'Площадь жилого помещения': '{$square}'
            }";
            
        // Отправляем запрос по API        
        $result_api = Yii::$app->client_api->accountRegister($data);
        // Проверяем текущую базу на существование лицевого счета
        $is_account = PersonalAccount::findAccountBeforeRegister($account);
        
        if ($is_account == true) {
            $errorMsg = 'Указанный лицевой счет используется';
            $this->addError('account_number', $errorMsg);            
        } 
        
//        if ($is_account == false && ($result_api['success'] == 'error')) {
//            $this->addError('account_number', 'Регистрационные данные лицевого счета введены некорректно');
//            return false;
//        }
        
//        $this->client_info = $result_api['user_info'];
        $this->client_info = Yii::$app->params['User_info'];
        
    }    
    
    public function createAccount() {
        
        if (!$this->validate()) {
            return false;
        }
        
        $house_info = $this->client_info;
        if ($this->checkHouse($house_info)) {
            return true;
        }
        
        return false;
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
    private function checkHouse($house_info) {
        
        $is_house = Houses::find()
                ->where(['houses_town' => $house_info['Жилая площадь']['Город']])
                ->andWhere(['houses_street' => $house_info['Жилая площадь']['Улица']])
                ->andWhere(['houses_number_house' => $house_info['Жилая площадь']['Номер дома']])
                ->one();
        
        $is_flat = Flats::find()
                ->where(['flats_porch' => $house_info['Жилая площадь']['Номер подъезда']])
                ->andWhere(['flats_floor' => $house_info['Жилая площадь']['Номер этажа']])
                ->andWhere(['flats_number' => $house_info['Жилая площадь']['Номер квартиры']])
                ->andWhere(['flats_rooms' => $house_info['Жилая площадь']['Количество комнат']])
                ->andWhere(['flats_square' => $this->square])
                ->asArray()
                ->one();
        
        if ($is_house == null && $is_flat == null) {
            $this->createHouseAndFlat($house_info);
        } elseif ($is_house != null && $is_flat == null) {
            $this->createFlat($is_house->houses_id, $house_info);
        }
        
        return true;
        
    }
    
    /*
     * Создание нового Лицевого счета, с закрепленным домом и квартирой
     */
    private function createHouseAndFlat($data) {
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $house = new Houses();
            $house->houses_town = $data['Жилая площадь']['Город'];
            $house->houses_street = $data['Жилая площадь']['Улица'];
            $house->houses_number_house = $data['Жилая площадь']['Номер дома'];
            
            if (!$house->save()) {
                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $house->getFirstErrors()));
            }
            
            $flat = new Flats();
            $flat->flats_house_id = $house->houses_id;
            $flat->flats_porch = $data['Жилая площадь']['Номер подъезда'];
            $flat->flats_floor = $data['Жилая площадь']['Номер этажа'];
            $flat->flats_number = $data['Жилая площадь']['Номер квартиры'];
            $flat->flats_rooms = $data['Жилая площадь']['Количество комнат'];
            $flat->flats_square = $this->square;

            if (!$flat->save()) {
                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $flat->getFirstErrors()));
            }
            
            $account = new PersonalAccount();
            $account->account_number = $this->account_number;
            $account->account_organization_id = 1;
            $account->account_balance = 0;
            $account->personal_clients_id = Yii::$app->userProfile->clientID;
            $account->personal_rent_id = null;
            $account->personal_flat_id = $flat->flats_id;

            if (!$account->save()) {
                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $account->getFirstErrors()));
            }
            
            $transaction->commit();
            
        } catch (Exception $ex) {            
            $transaction->rollBack();
            var_dump($ex);
            die();
        }        
        
    }

    /*
     * Создание нового Лицевого счета, с закрепленным домом и квартирой
     */    
    private function createFlat($houses_id, $data) {
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            
            $flat = new Flats();
            $flat->flats_house_id = $houses_id;
            $flat->flats_porch = $data['Жилая площадь']['Номер подъезда'];
            $flat->flats_floor = $data['Жилая площадь']['Номер этажа'];
            $flat->flats_number = $data['Жилая площадь']['Номер квартиры'];
            $flat->flats_rooms = $data['Жилая площадь']['Количество комнат'];
            $flat->flats_square = $this->square;

            if (!$flat->save()) {
                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $flat->getFirstErrors()));
            }
            
            $account = new PersonalAccount();
            $account->account_number = $this->account_number;
            $account->account_organization_id = 1;
            $account->account_balance = 0;
            $account->personal_clients_id = Yii::$app->userProfile->clientID;
            $account->personal_rent_id = null;
            $account->personal_flat_id = $flat->flats_id;

            if (!$account->save()) {
                throw new \yii\db\Exception('Ошибка создания новой записи' . 'Ошибка: ' . join(', ', $account->getFirstErrors()));
            }
            
            $transaction->commit();
            
        } catch (Exception $ex) {            
            $transaction->rollBack();
            var_dump($ex);
            die();
        }
        
    }    
    
    public function attributeLabels() {
        
        return [
            'account_number' => 'Номер лицевого счета',
            'last_sum' => 'Сумма предыдущей квитанции',
            'square' => 'Площадь квартиры, указанной в квитанции',
        ];
        
    }
    
}
