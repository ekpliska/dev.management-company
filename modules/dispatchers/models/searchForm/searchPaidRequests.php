<?php

    namespace app\modules\dispatchers\models\searchForm;
    use yii\data\ActiveDataProvider;
    use app\models\PaidServices;

/**
 * Форма поиска по заявкам, через дополнительное навигационное меню
 */
class searchPaidRequests extends PaidServices {
    
    public $value;
    public $house_id;
    public $date_start;
    public $date_finish;
    
    public function rules() {
        
        return [
            [['value'], 'string'],
            [['value'], 'trim'],
            [['house_id'], 'integer'],
            
            [['services_name_services_id'], 'integer'],
            
            [['services_specialist_id', 'status'], 'integer'],
            
            [['date_start', 'date_finish'], 'date', 'format' => 'php:Y-m-d']
            
        ];
        
    }
    
    public function search($params) {
        
        $query = (new \yii\db\Query)
                ->select('ps.services_id as id, '
                        . 'ps.services_number as number, '
                        . 'ps.services_comment as comment, '
                        . 'ps.created_at as date_create, ps.date_closed as date_close, '
                        . 'ps.status as status, '
                        . 'ps.services_name_services_id as services_name_services_id, ps.services_specialist_id as services_specialist_id, '
                        . 'pa.account_number as account_number, '
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
        
        $data_provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 30,
            ],
        ]);
        
        $this->load($params);
        
        if (!$this->validate()) {
            return $data_provider;
        }
        
        if (!empty($this->value)) {
            $_value = (int)$this->value;
            $query->andFilterWhere(['=', 'services_number', $_value]);
            $query->orFilterWhere(['=', 'account_number', $_value]);
        }
        
        $query->andFilterWhere(['=', 'services_name_services_id', $this->services_name_services_id]);
        $query->andFilterWhere(['=', 'services_specialist_id', $this->services_specialist_id]);
        $query->andFilterWhere(['=', 'status', $this->status]);
        
        if (!empty($this->date_start)) {
            $date_start = strtotime($this->date_start);
            $date_finish = strtotime($this->date_finish);
            $query->andFilterWhere(['between', 'created_at', $date_start, $date_finish]);
        }
        
        return $data_provider;
        
    }
    
    
}
