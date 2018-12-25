<?php

    namespace app\modules\managers\models;
    use app\models\Employees;

/**
 *  Адмнистраторы
 */
class Managers extends Employees {
    
    public static function getListManagers() {
        
        $query = (new \yii\db\Query)
                ->select('e.employee_id as id, '
                        . 'e.employee_surname as surname, e.employee_surname as name, e.employee_second_name as second_name,'
                        . 'u.user_login as login,'
                        . 'au.item_name as role')
                ->from('employees as e')
                ->join('LEFT JOIN', 'user as u', 'e.employee_id = u.user_employee_id')                
                ->join('LEFT JOIN','auth_assignment as au','au.user_id = u.user_id')
                ->where(['au.item_name' => 'administrator'])
                ->orderBy(['e.employee_surname' => SORT_ASC]);
        
        return $query;
    }
    
}
