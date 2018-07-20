<?php
    namespace app\modules\clients\models;
    use Yii;
    use yii\base\Model;
    use app\models\PersonalAccount;

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
    
    public $flat;


    public function rules() {
        return [
            [[
                'account_number', 'account_organization_id', 'account_last_sum',
                'account_client_surname', 'account_client_name', 'account_client_secondname',
                'flat'], 'required'],
            
            //['account_number', 'string', 'min' => 11, 'max' => 11],
            ['account_number', 'checkPersonalAccount'],
            
        ];
    }
    
    /*
     * Валидация номера лицевого счета
     * Если указанный лицевой счет существует в БД и он закреплен за собственником - то он считается активным
     */
    public function checkPersonalAccount() {
        $personal_account = PersonalAccount::find()
                ->andWhere(['account_number' => $this->account_number])
                ->andWhere(['not', ['personal_clients_id' => null]])
                ->asArray()
                ->one();
        if ($personal_account) {
            $errorMsg = 'Указанный лицевой счет уже используется';
            $this->addError('account_number', $errorMsg);
        }
    }
    
    public function saveRecord() {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $new_account = new PersonalAccount();
            $new_account->account_number = $this->account_number;
            $new_account->account_organization_id = $this->account_organization_id;
            $new_account->account_balance = $this->account_last_sum;
            $new_account->personal_clients_id = '00';
            $new_account->personal_rent_id = $this->account_rent;
            $new_account->personal_house_id = $this->flat;
            $new_account->save(false);
            $transaction->commit();
        }
        catch (Exception $e) {
            $transaction->rollBack();
            $e->getMessage();
        }
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
            
            'flat' => 'Адрес',
        ];
    }
    
    
   
}
