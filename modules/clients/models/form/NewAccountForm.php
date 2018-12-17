<?php

    namespace app\modules\clients\models\form;
    use yii\base\Model;

/**
 * Создание нового лицевого счета
 */
class NewAccountForm extends Model {
    
    public $account_number;
    public $last_sum;
    public $square;
    
    public function rules() {
        
        return [
            [['account_number', 'last_sum', 'square'], 'required'],
            
            ['account_number', 'string', 'min' => 11, 'max' => 11],
            
//            ['last_sum', 
//                'match', 
//                'pattern' => '/^[0-9]{1,12}(\.[0-9]{0,4})?+$/iu', 
//                'message' => 'Сумма последней квитанции указана не верно. Пример: 2578.70'],
//            
//            ['square', 'match', 'pattern'=>'/^[0-9]{1,12}(\.[0-9]{0,4})?+$/iu', 'message' => 'Площадь жилого помещения указана не верно. Пример, 80.27'],
            
            
        ];
    }
    
    public function createAccount() {
        
        if (!$this->validate()) {
            return false;
        }
        
        return true;
        
    }
    
    public function attributeLabels() {
        
        return [
            'account_number' => 'Номер лицевого счета',
            'last_sum' => 'Сумма предыдущей квитанции',
            'square' => 'Площадь квартиры, указанной в квитанции',
        ];
        
    }
    
}
