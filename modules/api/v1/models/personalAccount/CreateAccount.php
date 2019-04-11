<?php

    namespace app\modules\api\v1\models\profile;
    use Yii;
    use yii\base\Model;

/*
 * Сздание лицевого счета
 */
class CreateAccount extends Model {
    
    public $account_number;
    public $last_sum;
    public $square;
    
    /*
     * Правила валидации
     */
    public function rules() {
        
        return [
            [['account_number', 'last_sum', 'square'], 'required', 'message' => 'Поле обязательно для заполнения'],
//            ['account_number', 'string', 'min' => 12, 'max' => 12],
            
            [['square'], 'double', 'message' => 'Площадь жилого помещения указана не верно. Пример, 80.27'],
            
            ['last_sum', 'double', 'message' => 'Сумма предыдущей квитанции указана не верно. Пример: 2578.70'],
            
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
    
    
}
