<?php

    namespace app\modules\dispatchers\models;
    use Yii;
    use app\models\Requests as BaseRequests;
    use app\models\StatusRequest;
    use app\models\Notifications;

/**
 * Заявки
 */
class RequestsList extends BaseRequests {
    
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
     * Назначение специалиста
     */
    public function chooseSpecialist($specialist_id) {
        
        // Формируем уведомление
        if (empty($this->requests_specialist_id)) {
            Notifications::createNoticeStatus(Notifications::TYPE_CHANGE_STATUS_IN_REQUEST, $this->requests_id, StatusRequest::STATUS_PERFORM);
        }
        
        $this->requests_dispatcher_id = Yii::$app->profileDispatcher->employeeID;
        $this->requests_specialist_id = $specialist_id;
        $this->status = StatusRequest::STATUS_PERFORM;
        $this->is_accept = true;
        
        
        if (!$this->save()) {
            return false;
        }
        
        
        return true;
    }
    
    /*
     * Отключение чата в заявке
     */
    public static function closeChat($request_id) {
        
        $request = self::findOne($request_id);
        
        if ($request->status != StatusRequest::STATUS_CLOSE && $request != StatusRequest::STATUS_REJECT) {
            return false;
        }
        
        if ($request->close_chat == BaseRequests::CHAT_OPEN) {
            $request->close_chat = BaseRequests::CHAT_CLOSE;
        } else {
            $request->close_chat = BaseRequests::CHAT_OPEN;
        }
        return $request->save(false) ? true : false;
        
    }
    
}
