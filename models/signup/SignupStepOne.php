<?php

    namespace app\models\signup;
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
            [['account_number', 'square'], 'integer'],
            ['last_summ', 'integer'],
        ];
    }
    
    public function afterValidate() {
        
        if (!$this->hasErrors()) {
            
            $account = $this->account_number;
            $summ = $this->last_summ;
            $square = $this->square;

            $is_account = PersonalAccount::findAccountBeforeRegister($account, $summ, $square);

            if ($is_account == null) {
                $this->addError($attribute, 'Вы используете некорректные данные');
                return false;
            }
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
