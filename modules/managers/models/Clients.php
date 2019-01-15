<?php

    namespace app\modules\managers\models;
    use app\models\Clients as BaseClients;

/*
 * Клиенты
 * 
 * Наследуется от основной модели Клиенты
 */
class Clients extends BaseClients {
    
    public static function getListClients() {
        
        $query = (new \yii\db\Query)
                ->select('c.clients_id as client_id, '
                        . 'c.clients_name as name, c.clients_second_name as second_name, c.clients_surname as surname, '
                        . 'a.account_number as number, a.account_balance as balance, '
                        . 'u.status as status, '
                        . 'h.houses_gis_adress as full_adress, '
                        . 'f.flats_porch as porch, f.flats_floor as floor, '
                        . 'f.flats_number as flat')
                ->from('clients as c')
                ->join('LEFT JOIN', 'user as u', 'u.user_client_id = c.clients_id')
                ->join('LEFT JOIN', 'personal_account as a', 'a.personal_clients_id = c.clients_id')
                ->join('LEFT JOIN', 'flats as f', 'f.flats_id = a.personal_flat_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                ->orderBy('c.clients_surname')
                ->groupBy('a.account_number');
        
        return $query;
        
    }
    
    public static function getClientInfo($user_id) {
        
        $query = (new \yii\db\Query)
                ->select('c.clients_id as client_id, '
                        . 'c.clients_name as name, c.clients_second_name as second_name, c.clients_surname as surname, '
                        . 'a.account_number as number, '
                        . 'u.user_mobile as mobile, u.user_email as email, '
                        . 'u.user_photo as photo, '
                        . 'u.status as status, '
                        . 'u.created_at as created_at, u.last_login as last_login, '
                        . 'h.houses_gis_adress as full_adress, '
                        . 'h.houses_number as houses_number, '
                        . 'f.flats_number as flat')
                ->from('clients as c')
                ->join('LEFT JOIN', 'user as u', 'u.user_client_id = c.clients_id')
                ->join('LEFT JOIN', 'personal_account as a', 'a.personal_clients_id = c.clients_id')
                ->join('LEFT JOIN', 'flats as f', 'f.flats_id = a.personal_flat_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                ->where(['u.user_id' => $user_id])
                ->one();
        
        return $query;
        
    }
    
}
