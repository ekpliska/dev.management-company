<?php

    namespace app\models\signup;
    use Yii;
    use yii\base\Model;
    use app\models\PersonalAccount;

/**
 * Регистрация, шаг один
 */
class SignupStepOne extends Model {
    
    public $account_number;
    public $last_summ;
    public $square;

    public function rules() {
        return [
            [['account_number', 'last_summ', 'square'], 'required'],
            
            ['last_summ', 'double', 'min' => 00.00, 'max' => 100000.00, 'message' => 'Цена услуги указана не верно. Пример: 790.70'],
            ['square', 'double', 'min' => 20.00, 'max' => 100000.00, 'message' => 'Площадь жилого помещения указана не верно. Пример, 80.27'],
            
        ];
    }
    
    public function afterValidate() {
        
        if (!$this->hasErrors()) {
            
            $account = $this->account_number;
            $summ = $this->last_summ;
            $square = $this->square;
            
            // Формируем регистрационные данные на отправку по API
            $data_array = [
                "Номер лицевого счета" => "{$account}",
                "Сумма последней квитанции" => "{$summ}",
                "Площадь жилого помещения" => "{$square}",
            ];
            
            // Преобразуем массив в json
            $data_json = json_encode($data_array, JSON_UNESCAPED_UNICODE);
            
            // Отправляем запрос по API        
            $result_api = Yii::$app->client_api->accountRegister($data_json);
            
            // Проверяем текущую базу на существование лицевого счета
            $is_account = PersonalAccount::findAccountBeforeRegister($account);
            
            if ($is_account == true) {
                $this->addError($attribute, 'Указанный номер номер лицевого счета зарегистрирован');
                return false;
            }
            
            if ($is_account == false && $result_api['success'] == false) {
                $this->addError($attribute, 'Регистрационные данные лицевого счета введены некорректно');
                return false;
            }
            
            // Записываем в сессию пришедщие данные по API
            Yii::$app->session['UserInfo'] = $result_api;
            
            return true;

        }
        
        parent::afterValidate();

    }
    
    
    public function attributeLabels() {
        return [
            'account_number' => 'Номер лицевого счета',
            'last_summ' => 'Сумма последней квитанции',
            'square' => 'Площадь жилого помещения',
        ];
    }
    
    
    
}
