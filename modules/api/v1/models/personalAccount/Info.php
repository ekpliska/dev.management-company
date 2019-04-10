<?php
    
    namespace app\modules\api\v1\models\personalAccount;
    use app\models\PersonalAccount;

class Info extends PersonalAccount {
    
    public static function getInfo($account) {
        
        $info = (new \yii\db\Query)
                ->from('personal_account')
                ->select([
                    'account_number', 'account_balance', 
                    'organizations_name',
                    'CONCAT_WS(" ", clients_surname, clients_name, clients_second_name) as client_fullname',
                    '',
                    'CONCAT_WS(" ", rents_surname, rents_name, rents_second_name) as rent_fullname',
                    'rents_mobile'])
                ->join('LEFT JOIN', 'clients', 'personal_clients_id = clients_id')
                ->join('LEFT JOIN', 'flats', 'flats_id = personal_flat_id')
                ->join('LEFT JOIN', 'houses', 'flats_house_id = houses_id')
                ->join('LEFT JOIN', 'rents', 'personal_rent_id = rents_id')
                ->join('LEFT JOIN', 'organizations', 'account_organization_id = organizations_id')
                ->where(['account_number' => $account])
                ->one();
        
        return $info;
    }
    
}
