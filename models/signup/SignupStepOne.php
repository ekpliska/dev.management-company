<?php

    namespace app\models\signup;
    use yii\base\Model;

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
    
    
    
}
