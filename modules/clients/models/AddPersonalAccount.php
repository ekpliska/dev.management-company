<?php
    namespace app\modules\clients\models;
    use yii\base\Model;

/**
 * Добавление лицевого счета
 */
class AddPersonalAccount extends Model {
    
    public $account_number;
    public $account_organization_id;
    public $account_last_sum;
    
    public $account_client_surname;
    public $account_client_name;
    public $account_client_secondname;
    
    public $account_rent;        
    
    public function rules() {
        return [
            [[
                'account_number', 'account_organization_id', 'account_last_sum',
                'account_client_surname', 'account_client_name', 'account_client_secondname'], 'required'],
            
        ];
    }
    
    public function addAccount() {
        // 
    }
    
    public function attributeLabels() {
        return [
            'account_number' => 'Номер лицевого счета',
            'account_organization_id' => 'Управляющая организация',
            'account_last_sum' => 'Сумма последней квитанции',
            
            'account_client_surname' => 'Фамилия собственника', 
            'account_client_name' => 'Имя собственника', 
            'account_client_secondname' => 'Отчество собственника',
            
            'account_rent' => 'Арендатор',
        ];
    }
   
}
