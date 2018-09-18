<?php

    namespace app\modules\managers\models;
    use app\models\Employers;

/**
 * Диспетчеры
 */
class Dispatchers extends Employers {
    
    public static function getListDispatchers() {
        
        $query = (new \yii\db\Query)
                ->select('e.employers_id as id, '
                        . 'e.employers_surname as surname, e.employers_name as name, e.employers_second_name as second_name,'
                        . 'u.user_login as login,'
                        . 'au.item_name as role')
                ->from('employers as e')
                ->join('LEFT JOIN', 'user as u', 'e.employers_id = u.user_employee_id')                
                ->join('LEFT JOIN','auth_assignment as au','au.user_id = u.user_id')
                ->where(['au.item_name' => 'dispatcher']);
        
        return $query;
        
    }

    
}
