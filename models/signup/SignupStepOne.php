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
            
            ['account_number', 'string', 'min' => 11, 'max' => 11],
            
            ['last_summ', 'match', 'pattern'=>'/^[0-9]{1,12}(\.[0-9]{0,4})?+$/iu', 'message' => 'Сумма последней квитанции указана не верно. Пример: 2578.70'],
            ['square', 'match', 'pattern'=>'/^[0-9]{1,12}(\.[0-9]{0,4})?+$/iu', 'message' => 'Площадь жилого помещения указана не верно. Пример, 80.27'],

        ];
    }
    
    public function afterValidate() {
        
        if (!$this->hasErrors()) {
            
            $account = $this->account_number;
            $summ = $this->last_summ;
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
                $this->addError($attribute, 'Указанный номер номер лицевого счета зарегистрирован');
                return false;
            }
            
            if ($is_account == false && ($result_api['success'] == 'error')) {
                $this->addError($attribute, 'Регистрационные данные лицевого счета введены некорректно');
                return false;
            }
            
            // Записываем в сессию пришедщие данные по API
            Yii::$app->session['UserInfo'] = Yii::$app->params['User_info'];
            
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
