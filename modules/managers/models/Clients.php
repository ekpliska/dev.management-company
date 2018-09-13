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
                        . 'h.houses_town as town, '
                        . 'h.houses_street as street, h.houses_number_house as house, '
                        . 'h.houses_porch as porch, h.houses_floor as floor, '
                        . 'h.houses_flat as flat')
                ->from('clients as c')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_client_id = c.clients_id')
                ->join('LEFT JOIN', 'personal_account as a', 'a.personal_clients_id = c.clients_id')
                ->join('LEFT JOIN', 'user as u', 'u.user_client_id = c.clients_id')
                ->orderBy('c.clients_surname')
                ->groupBy('a.account_number');
        
        return $query;
        
    }
    
}
