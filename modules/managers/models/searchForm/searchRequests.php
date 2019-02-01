<?php

    namespace app\modules\managers\models\searchForm;
    use yii\data\ActiveDataProvider;
    use app\models\Requests;

/**
 * Форма поиска по заявкам, через дополнительное навигационное меню
 */
class searchRequests extends Requests {
    
    public $value;
    public $date_start;
    public $date_finish;
    
    public function rules() {
        
        return [
            [['value'], 'string'],
            [['value'], 'trim'],
            
            [['requests_type_id'], 'integer'],
            
            [['requests_specialist_id', 'requests_specialist_id'], 'integer'],
            
            [['date_start', 'date_finish'], 'date', 'format' => 'php:Y-m-d']
            
        ];
        
    }
    
    public function search($params) {
        
        $query = (new \yii\db\Query)
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
            $query->andFilterWhere(['=', 'requests_ident', $_value]);
        }
        
        $query->andFilterWhere(['=', 'requests_type_id', $this->requests_type_id]);
        $query->andFilterWhere(['=', 'requests_specialist_id', $this->requests_specialist_id]);
        
        if (!empty($this->date_start)) {
            $date_start = strtotime($this->date_start);
            $date_finish = strtotime($this->date_finish);
            $query->andFilterWhere(['between', 'created_at', $date_start, $date_finish]);
        }
        
        return $data_provider;
        
    }
    
    
}
