<?php

    namespace app\modules\api\v1\models\personalAccount;
    use Yii;
    use yii\base\Model;
    use app\models\PersonalAccount;
    use app\models\Houses;
    use app\models\Flats;

/*
 * Сздание лицевого счета
 */
class CreateAccount extends Model {
    
    public $account_number;
    public $last_sum;
    public $square;
    
    public $client_info = [];
    
    /*
     * Правила валидации
     */
    public function rules() {
        
        return [
            [['account_number', 'last_sum', 'square'], 'required'],
//            ['account_number', 'string', 'min' => 12, 'max' => 12],
            
            ['account_number', 'checkPersonalAccount'],
            
            ['client_info', 'safe'],
            
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
            $this->addError('account_number', 'Указанный номер номер лицевого счета зарегистрирован');
            return false;
        }

        if ($is_account == false && $result_api['Лицевой счет']['success'] == false) {
            $this->addError('account_number', 'Регистрационные данные лицевого счета введены некорректно');
            return false;
        }

        $this->client_info = $result_api;
        
        return true;
        
    }    
    
    /*
     * Создание лицевого счета
     */
    public function save() {
        
        if (!$this->validate()) {
            return false;
        }
        
        // Получаем данные пришедшие по API
        $house_info = $this->client_info;
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $house = new Houses();
            $house_data_api = $house_info['Жилая площадь'];
            // Проверяем существование дома у базе
            $house_id = $house::isExistence(
                    $house_data_api['House adress'], 
                    $house_data_api['Полный адрес Собственника'],
                    $house_data_api['Номер дома']);

            // Создаем запись квартиры
            $flat = new Flats();
            $flat->flats_house_id = $house_id;
            $flat->flats_porch = $house_data_api['Номер подъезда'];
            $flat->flats_floor = $house_data_api['Номер этажа'];
            $flat->flats_number = $house_data_api['Номер квартиры'];
            $flat->flats_rooms = $house_data_api['Количество комнат'];
            $flat->flats_square = $this->square;

            if (!$flat->save()) {
                return false;
            }

            // Создаем запись нового лицевого счета
            $account = new PersonalAccount();
            $account->account_number = $this->account_number;
            $account->account_organization_id = 1;
            $account->account_balance = $house_info['Лицевой счет']['Баланс'];
            $account->personal_clients_id = Yii::$app->user->identity->user_client_id;
            $account->personal_rent_id = null;
            $account->personal_flat_id = $flat->flats_id;

            if (!$account->save()) {
                return false;
            }
            
            $transaction->commit();
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        
        return true;
    }
    
    public function attributeLabels() {
        
        return [
            'account_number' => 'Номер лицевого счета',
            'last_sum' => 'Сумма последней квитации',
            'square' => 'Площадь жилого помещения',
        ];
        
    }
    
}
