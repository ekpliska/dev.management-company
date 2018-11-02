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
    public $client_id;
    
    public function rules() {
        
        return [
            [['account_number', 'last_sum', 'square', 'client_id'], 'required'],
            
            ['account_number', 'string', 'min' => 11, 'max' => 11],
            
//            ['last_summ', 'match', 'pattern'=>'/^[0-9]{1,12}(\.[0-9]{0,4})?+$/iu'],
//            
//            ['square', 'match', 'pattern'=>'/^[0-9]{1,12}(\.[0-9]{0,4})?+$/iu'],
            
            ['client_id', 'integer'],
            
        ];
    }
    
    public function attributeLabels() {
        
        return [
            'account_number' => 'Номер лицевого счета',
            'last_sum' => 'Сумма предыдущей квитанции',
            'square' => 'Площадь квартиры, указанной в квитанции',
            'client_id' => 'Собственник',
        ];
        
    }
    
}
