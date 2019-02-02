<?php

    namespace app\modules\api\models;
    use app\models\User;

/**
 * Профиль пользователя
 */
class UserProfile extends User {
    
    public static function userProfile($user_id) {
        
        $query = (new \yii\db\Query)
                ->select('c.clients_name as name, c.clients_second_name as second_name, c.clients_surname as surname, '
                        . 'u.user_login as login, '
                        . 'u.user_mobile as user_mobile, '
                        . 'u.user_email as email, u.user_photo as photo, '
                        . 'u.created_at as date_created , u.last_login as last_login, '
                        . 'u.status as status, '
                        . 'pa.account_number as account_number,'
                        . 'h.houses_gis_adress as gis_adress, h.houses_number, f.flats_number')
                ->from('user as u')
                ->join('LEFT JOIN', 'clients as c', 'u.user_client_id = c.clients_id')
                ->join('LEFT JOIN', 'personal_account as pa', 'u.user_client_id = pa.personal_clients_id')
                ->join('LEFT JOIN', 'flats as f', 'f.flats_id = pa.personal_flat_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                ->where(['u.user_id' => $user_id])
                ->andWhere(['=', 'isActive', '1'])
                ->one();
        
        return $query;
        
    }
    
}
