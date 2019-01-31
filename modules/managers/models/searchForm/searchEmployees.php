<?php

    namespace app\modules\managers\models\searchForm;
    use app\models\Employees;
    use yii\data\ActiveDataProvider;

/**
 * Форма поиска по сотрудника, через дополнительное навигационное меню
 * 
 */
class searchEmployees extends \app\models\Employees {
    
    public $name;
    
    public function rules() {
        
        return [
            
            [['name'], 'string'],
            [['name'], 'trim'],
            [['employee_department_id', 'employee_posts_id'], 'integer'],
            
        ];
        
    }
    
    public function search($params, $role) {
        
        $query = (new \yii\db\Query)
                ->select('e.employee_id as employee_id, '
                        . 'e.employee_surname as employee_surname, e.employee_name as employee_name, e.employee_second_name as employee_second_name,'
                        . 'e.employee_department_id as employee_department_id, e.employee_posts_id as employee_posts_id, '
                        . 'd.department_name as department_name, p.post_name as post_name, '
                        . 'u.user_login as login, u.last_login as last_login,'
                        . 'au.item_name as role')
                ->from('employees as e')
                ->join('LEFT JOIN', 'user as u', 'e.employee_id = u.user_employee_id')
                ->join('LEFT JOIN', 'departments as d', 'e.employee_department_id = d.department_id')
                ->join('LEFT JOIN', 'posts as p', 'e.employee_posts_id = p.post_id')
                ->join('LEFT JOIN','auth_assignment as au','au.user_id = u.user_id')
                ->where(['au.item_name' => $role])
                ->orderBy(['e.employee_surname' => SORT_ASC]);
        
        $data_provider = new ActiveDataProvider([
            'query' => $query
        ]);
        
        $this->load($params);
        
        if (!$this->validate()) {
            return $data_provider;
        }
        
        if (!empty($this->name)) {
            $full_name = explode(' ', $this->name);
            $this->employee_surname = $full_name[0];
            $this->employee_name = count($full_name) == 2 ? $full_name[1] : $full_name[0];
            $this->employee_second_name = count($full_name) == 3 ? $full_name[2] : $full_name[0];
            
            $query->andFilterWhere(['or', 
                ['like', 'e.employee_surname', $this->employee_surname],
                ['like', 'e.employee_name', $this->employee_name],
                ['like', 'e.employee_second_name', $this->employee_second_name],
            ]);
            
        }
        
        $query->andFilterWhere(['=', 'employee_department_id', $this->employee_department_id]);
        $query->andFilterWhere(['=', 'employee_posts_id', $this->employee_posts_id]);
        
        return $data_provider;
        
    }
    
    
    
}
