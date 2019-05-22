<?php

    namespace app\modules\managers\models;
    use yii\data\ActiveDataProvider;
    use yii\helpers\ArrayHelper;
    use app\models\PaidServices as BasePaidServices;
    use app\models\StatusRequest;
    use app\helpers\FormatHelpers;
    use app\models\PersonalAccount;
    use app\models\Notifications;

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
                        . 's.service_name as service_name, '
                        . 'ed.employee_id as employee_id_d, '
                        . 'ed.employee_surname as surname_d, ed.employee_name as name_d, ed.employee_second_name as sname_d, '
                        . 'es.employee_id as employee_id_s, '
                        . 'es.employee_surname as surname_s, es.employee_name as name_s, es.employee_second_name as sname_s, '
                        . 'c.clients_surname as clients_surname, c.clients_second_name as clients_second_name, c.clients_name as clients_name, '
                        . 'h.houses_gis_adress as gis_adress, h.houses_number as house, '
                        . 'f.flats_porch as porch, f.flats_floor as floor, '
                        . 'f.flats_number as flat')
                ->from('paid_services as ps')
                ->join('LEFT JOIN', 'services as s', 's.service_id = ps.services_name_services_id')
                ->join('LEFT JOIN', 'category_services as cs', 'cs.category_id = s.service_category_id')
                ->join('LEFT JOIN', 'employees as ed', 'ed.employee_id = ps.services_dispatcher_id')
                ->join('LEFT JOIN', 'employees as es', 'es.employee_id = ps.services_specialist_id')
                ->join('LEFT JOIN', 'personal_account as pa', 'pa.account_id = ps.services_account_id')
                ->join('LEFT JOIN', 'flats as f', 'f.flats_id = pa.personal_flat_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                ->join('LEFT JOIN', 'clients as c', 'c.clients_id = pa.personal_clients_id')
                ->orderBy(['ps.created_at' => SORT_DESC]);
        
        return $requests;
    }
    
    /*
     * Формируем запрос на получение всех заявок на платные услуги
     * Стасут: Новая
     */
    public static function getOnlyNewPaidRequest() {
        
        $requests = (new \yii\db\Query)
                ->select('ps.services_id as id, '
                        . 'ps.services_number as number, '
                        . 'ps.services_comment as comment, '
                        . 'ps.created_at as date_create, ps.date_closed as date_close, '
                        . 'ps.status as status, '
                        . 'cs.category_name as category, '
                        . 's.service_name as service_name')
                ->from('paid_services as ps')
                ->join('LEFT JOIN', 'services as s', 's.service_id = ps.services_name_services_id')
                ->join('LEFT JOIN', 'category_services as cs', 'cs.category_id = s.service_category_id')
                ->join('LEFT JOIN', 'personal_account as pa', 'pa.account_id = ps.services_account_id')
                ->where(['status' => StatusRequest::STATUS_NEW])
                ->orderBy(['ps.created_at' => SORT_DESC]);

        $data_provider = new ActiveDataProvider([
            'query' => $requests,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 7,
            ],
        ]);

        return $data_provider;
    }    
    
    /*
     * Назначение диспетчера
     */
    public function chooseDispatcher($dispatcher_id) {
        
        $this->services_dispatcher_id = $dispatcher_id;
        $this->status = StatusRequest::STATUS_IN_WORK;
        
        // Формируем уведомление
        if (empty($this->status != StatusRequest::STATUS_IN_WORK)) {
            Notifications::createNoticeStatus(Notifications::TYPE_CHANGE_STATUS_IN_PAID_REQUEST, $this->services_id, StatusRequest::STATUS_IN_WORK);
        }
        
        return $this->save(false) ? true : false;
        
    }

    /*
     * Назначение специалиста
     */
    public function chooseSpecialist($specialist_id) {
        
        $this->services_specialist_id = $specialist_id;
        $this->status = StatusRequest::STATUS_PERFORM;
        
        // Формируем уведомление
        if (empty($this->status != StatusRequest::STATUS_PERFORM)) {
            Notifications::createNoticeStatus(Notifications::TYPE_CHANGE_STATUS_IN_PAID_REQUEST, $this->services_id, StatusRequest::STATUS_PERFORM);
        }
        
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
    
    public static function getPaidRequestBySpecialist($employee_id) {
        
        $paid_requestr_list = self::find()
                ->select(['services_name_services_id', 'services_number', 'created_at', 'status', 'category_name', 'service_name'])
                ->joinWith(['service', 'service.category'])
                ->where(['services_specialist_id' => $employee_id])
                ->andWhere(['!=', 'status', StatusRequest::STATUS_CLOSE])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->all();
        
        return $paid_requestr_list;
        
    }
    
    
}
