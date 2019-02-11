<?php

    namespace app\modules\api\v1\models;
    use app\models\User;

/**
 * Профиль пользователя
 */
class UserProfile extends User {
    
    public static function userProfile($user_id) {
        
        $user_info = [];
        $rent_info = [];
        
        $clients = (new \yii\db\Query)
                ->select('c.clients_id as clients_id, c.clients_name as name, c.clients_second_name as second_name, c.clients_surname as surname, '
                        . 'u.user_login as login, '
                        . 'u.user_mobile as user_mobile, '
                        . 'u.user_email as email, u.user_photo as photo, '
                        . 'u.last_login as last_login, '
                        . 'u.user_check_email as subscribe')
                ->from('user as u')
                ->join('LEFT JOIN', 'clients as c', 'u.user_client_id = c.clients_id')
                ->where(['u.user_id' => $user_id])
                ->one();
        
        $user_info['client_info'] = $clients;
        
        $personal_account = (new \yii\db\Query)
                ->select('pa.account_number as number, '
                        . 'r.rents_surname as rents_surname, r.rents_name as rents_name, r.rents_second_name as rents_second_name, '
                        . 'r.rents_mobile as rents_mobile, r.rents_mobile_more as other_phone, '
                        . 'h.houses_id as house_id, '
                        . 'h.houses_gis_adress as gis_adress, h.houses_number as houses_number, f.flats_number as flats_number')
                ->from('personal_account as pa')
                ->join('LEFT JOIN', 'clients as c', 'pa.personal_clients_id = c.clients_id')
                ->join('LEFT JOIN', 'rents as r', 'pa.personal_rent_id = r.rents_id')
                ->join('LEFT JOIN', 'flats as f', 'f.flats_id = pa.personal_flat_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                ->where(['pa.personal_clients_id' => $clients['clients_id']])
                ->all();
        
        foreach ($personal_account as $key => $account) {
            
            if (empty($account['rents_surname'])) {
                $rent_info = null;
                
            } else {
                $rent_info = [
                    'rents_surname' => $account['rents_surname'],
                    'rents_name' => $account['rents_name'],
                    'rents_second_name' => $account['rents_second_name'],
                    'rents_mobile' => $account['rents_mobile'],
                    'rents_mobile_more' => $account['rents_mobile_more'],
                ];
            }
            
            $living_area = [
                'house_id' => $account['house_id'],
                'gis_adress' => $account['gis_adress'],
                'houses_number' => $account['houses_number'],
                'flats_number' => $account['flats_number'],
            ];
            
            $user_info['personal_account'][$account['number']] = [
                'rent_info' => $rent_info,
                'living_area' => $living_area,
            ];
        }
        
        return $user_info;
        
    }
    
}
