<?php

    namespace app\modules\managers\models;
    use app\models\PaidServices as BasePaidServices;

/**
 * Заявки на платные услуги
 */
class PaidServices extends BasePaidServices {
    
    /*
     * Формируем запрос на получение всех заявок на платные услуги
     */
    public static function getAllPaidRequests() {
        
        $requests = (new \yii\db\Query)
                ->select('ps.services_id as id, '
                        . 'ps.services_number as number, '
                        . 'ps.services_comment as comment, '
                        . 'ps.created_at as date_create, ps.date_closed as date_close, '
                        . 'ps.status as status, '
                        . 'cs.category_name as category, '
                        . 's.services_name as service_name, '
                        . 'ed.employers_surname as surname_d, ed.employers_name as name_d, ed.employers_second_name as sname_d, '
                        . 'es.employers_surname as surname_s, es.employers_name as name_s, es.employers_second_name as sname_s, '
                        . 'h.houses_town as town, h.houses_street as street, '
                        . 'h.houses_number_house as house, h.houses_flat as flat')
                ->from('paid_services as ps')
                ->join('LEFT JOIN', 'category_services as cs', 'cs.category_id = ps.services_category_services_id')
                ->join('LEFT JOIN', 'services as s', 's.services_id = ps.services_name_services_id')
                ->join('LEFT JOIN', 'employers as ed', 'ed.employers_id = ps.services_dispatcher_id')
                ->join('LEFT JOIN', 'employers as es', 'es.employers_id = ps.services_specialist_id')
                ->join('LEFT JOIN', 'personal_account as pa', 'pa.account_id = ps.services_account_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = pa.personal_house_id')
                ->orderBy(['ps.created_at' => SORT_DESC]);
        
        return $requests;
    }
    
}
