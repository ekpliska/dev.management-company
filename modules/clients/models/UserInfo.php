<?php

    namespace app\modules\clients\models;
    use Yii;
    use app\models\User;
    
/**
 * Description of UserInfo
 *
 * @author Ekaterina
 */
class UserInfo extends User {
    
    public static function getUserInfo() {
        
        if (Yii::$app->user->can('AddNewRent')) {
            $info = (new \yii\db\Query)
                    ->select('c.clients_id as client_id, c.clients_name as name, c.clients_second_name as second_name, c.clients_surname as surname, '
                        . 'c.clients_mobile as mobile, c.clients_phone as phone, '
                        . 'u.user_id as user_id, u.user_login as login, '
                        . 'u.user_email as email, u.user_photo as photo, '
                        . 'u.created_at as date_created , u.date_login as last_login, '
                        . 'u.status, '
                        . 'pa.account_number as account')
                    ->from('user as u')
                    ->join('LEFT JOIN', 'clients as c', 'u.user_client_id = c.clients_id')
                    ->join('LEFT JOIN', 'personal_account as pa', 'u.user_client_id = pa.personal_clients_id')
                    ->where(['u.user_id' => Yii::$app->user->identity->user_id])
                    ->one();
        } else {
            $info = (new \yii\db\Query)
                    ->select('r.rents_id as client_id, r.rents_name as name, r.rents_second_name as second_name, r.rents_surname as surname, '
                        . 'r.rents_mobile as mobile, r.rents_mobile_more as phone, '
                        . 'u.user_id as user_id, u.user_login as login, '
                        . 'u.user_email as email, u.user_photo as photo, '
                        . 'u.created_at as date_created , u.date_login as last_login, '
                        . 'u.status, '
                        . 'pa.account_number as account')
                    ->from('user as u')
                    ->join('LEFT JOIN', 'rents as r', 'u.user_rent_id = r.rents_id')
                    ->join('LEFT JOIN', 'personal_account as pa', 'u.user_rent_id = pa.personal_rent_id')
                    ->where(['u.user_id' => Yii::$app->user->identity->user_id])
                    ->one();
        }
        
        return $info;
    }
    
}
