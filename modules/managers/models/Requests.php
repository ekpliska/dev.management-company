<?php

    namespace app\modules\managers\models;
    use yii\helpers\ArrayHelper;
    use app\models\Requests as BaseRequests;
    use app\models\StatusRequest;
    use app\helpers\FormatHelpers;
    use app\models\PersonalAccount;

/**
 *  Завяки
 */
class Requests extends BaseRequests {
    
    /*
     * Формируем запрос на получение всех заявок
     */
    public static function getAllRequests() {
        
        $requests = (new \yii\db\Query)
                ->select('r.requests_id as requests_id, r.requests_ident as number, '
                        . 'r.requests_grade as grade, r.requests_comment as comment, '
                        . 'r.created_at as date_create, r.date_closed as date_close, '
                        . 'r.status as status, '
                        . 'tr.type_requests_name as category, '
                        . 'ed.employee_id as employee_id_d, ed.employee_surname as surname_d, ed.employee_name as name_d, ed.employee_second_name as sname_d, '
                        . 'es.employee_id as employee_id_s, es.employee_surname as surname_s, es.employee_name as name_s, es.employee_second_name as sname_s, '
                        . 'c.clients_surname as clients_surname, c.clients_second_name as clients_second_name, c.clients_name as clients_name, '
                        . 'h.houses_gis_adress as gis_adress, h.houses_number as house, '
                        . 'f.flats_porch as porch, f.flats_floor as floor, '
                        . 'f.flats_number as flat')
                ->from('requests as r')
                ->join('LEFT JOIN', 'type_requests as tr', 'tr.type_requests_id = r.requests_type_id')
                ->join('LEFT JOIN', 'employees as ed', 'ed.employee_id = r.requests_dispatcher_id')
                ->join('LEFT JOIN', 'employees as es', 'es.employee_id = r.requests_specialist_id')
                ->join('LEFT JOIN', 'personal_account as pa', 'pa.account_id = r.requests_account_id')
                ->join('LEFT JOIN', 'flats as f', 'f.flats_id = pa.personal_flat_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                ->join('LEFT JOIN', 'clients as c', 'c.clients_id = pa.personal_clients_id')
                ->orderBy(['r.created_at' => SORT_DESC]);
        
        return $requests;
    }
    
    /*
     * Последние 10 заявок, со статусом Новая
     */
    public static function getOnlyNewRequest($count = 10) {
        
        $requests = (new \yii\db\Query)
                ->select('r.requests_id as requests_id, r.requests_ident as number, '
                        . 'r.created_at as date_create, '
                        . 'r.status as status, '
                        . 'tr.type_requests_name as type_requests, '
                        . 'h.houses_gis_adress as gis_adress, h.houses_number as house, '
                        . 'f.flats_porch as porch, f.flats_floor as floor, '
                        . 'f.flats_number as flat')
                ->from('requests as r')
                ->join('LEFT JOIN', 'type_requests as tr', 'tr.type_requests_id = r.requests_type_id')
                ->join('LEFT JOIN', 'personal_account as pa', 'pa.account_id = r.requests_account_id')
                ->join('LEFT JOIN', 'flats as f', 'f.flats_id = pa.personal_flat_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                ->where(['status' => StatusRequest::STATUS_NEW])
                ->limit($count)
                ->orderBy(['r.created_at' => SORT_DESC])
                ->all();
        
        return $requests;
        
    }
    
    /*
     * Назначение диспетчера
     */
    public function chooseDispatcher($dispatcher_id) {
        
        $this->requests_dispatcher_id = $dispatcher_id;
        $this->status = StatusRequest::STATUS_IN_WORK;
        return $this->save(false) ? true : false;
        
    }

    /*
     * Назначение специалиста
     */
    public function chooseSpecialist($specialist_id) {
        
        $this->requests_specialist_id = $specialist_id;
        $this->status = StatusRequest::STATUS_PERFORM;
        return $this->save(false) ? true : false;
    }
    
    /*
     * Получить адреса Пользователя, при редактировании завки
     */
    public function getUserAdress($phone) {
        
        $client_info = User::findByPhone($phone);
        $client_id = $client_info['user_client_id'] ? $client_info['user_client_id'] : $client_info['user_rent_id'];
        
        $house_list = PersonalAccount::find()
                ->select(['account_id', 'houses_gis_adress', 'houses_number', 'flats_number', 'personal_flat_id'])
                ->joinWith(['flat', 'flat.house'])
                ->andWhere(['personal_clients_id' => $client_id])
                ->orWhere(['personal_rent_id' => $client_id])
                ->asArray()
                ->all();
        
        $house_lists = ArrayHelper::map($house_list, 'account_id', function ($data) {
            return FormatHelpers::formatFullAdress($data['houses_gis_adress'], $data['houses_number'], false, false, $data['flats_number']);
        });
        
        return $house_lists ? $house_lists : null;
        
    }
    
    /*
     * Получаем список всех Заявок и заявок на платные услуги для Специалиста
     */
    public static function getRequestBySpecialist($emplotee_id) {
        
        $requestr_list = self::find()
                ->select(['requests_id', 'requests_ident', 'status', 'created_at', 'requests_type_id', 'type_requests_name'])
                ->joinWith('typeRequest')
                ->where(['requests_specialist_id' => $emplotee_id])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->all();
        
        return $requestr_list;
        
    }
}
