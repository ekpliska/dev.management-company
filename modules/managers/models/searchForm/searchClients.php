<?php

    namespace app\modules\managers\models\searchForm;
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
/**
 * Модаль поиска собственников, через дополнительное навигационное меню
 */
class searchClients extends Model {
    
    public $input_value;
    
    public function run() {
        
        return [
            [['input_value'], 'string'],
            [['input_value'], 'trim'],
        ];
        
    }
    
    public function search($param) {
        
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
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 30,
            ],
        ]);
        
        $this->load($param);
        
        $query->andFilterWhere(['like', 'c.clients_name', $param])
                ->orFilterWhere(['like', 'clients_second_name', $param])
                ->orFilterWhere(['like', 'c.clients_surname', $param])
                ->orFilterWhere(['like', 'a.account_number', $param])
                ->orFilterWhere(['like', 'h.houses_gis_adress', $param]);
        
        if (!$this->validate()) {
          return $dataProvider;
        }
        return $dataProvider;
        
    }
    
    public function attributeLabels() {
        
        return [
            'input_value' => 'Поиск',
        ];
        
    }
    
}
