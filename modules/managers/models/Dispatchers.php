<?php

    namespace app\modules\managers\models;
    use app\models\Employers;

/**
 * Диспетчеры
 */
class Dispatchers extends Employers {
    
    public static function getListDispatchers() {
        
        $query = (new \yii\db\Query)
                ->from('employers as e')
                ->join('LEFT JOIN', 'user as e', 'e.employers_id = u.user_employee_id')                
                ->join('LEFT JOIN','auth_assignment as au','au.user_id = u.user_id')
                ;
        
        return $query;
        
    }

    
}
