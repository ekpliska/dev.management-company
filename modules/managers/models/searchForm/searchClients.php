<?php

    namespace app\modules\managers\models\searchForm;
    use yii\data\ActiveDataProvider;
    use app\models\Clients;
    
/**
 * Модаль поиска собственников, через дополнительное навигационное меню
 */
class searchClients extends Clients {
    
    public $input_value;
    
    public function rules() {
        
        return [
            [['input_value'], 'string'],
            [['input_value'], 'trim'],
        ];
        
    }
    
    public function search($params) {
        
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

        $data_provider = new ActiveDataProvider([
            'query' => $query
        ]);
        
        $this->load($params);
        
        if (!$this->validate()) {
            return $data_provider;
        }
        
        if (!empty($this->input_value)) {
            $full_name = explode(' ', $this->input_value);
            $this->clients_surname = $full_name[0];
            $this->clients_name = count($full_name) == 2 ? $full_name[1] : $full_name[0];
            $this->clients_second_name = count($full_name) == 3 ? $full_name[2] : $full_name[0];
            
            $query->andFilterWhere(['or', 
                ['like', 'c.clients_surname', $this->clients_surname],
                ['like', 'c.clients_name', $this->clients_name],
                ['like', 'c.clients_second_name', $this->clients_second_name],
                ['like', 'a.account_number', $full_name[0]],
            ]);
            
        }
        
        return $data_provider;
        
    }
    
    public function attributeLabels() {
        
        return [
            'input_value' => 'Поиск',
        ];
        
    }
    
}
