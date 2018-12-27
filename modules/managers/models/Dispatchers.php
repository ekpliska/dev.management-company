<?php

    namespace app\modules\managers\models;
    use app\models\Employees;
    use app\models\StatusRequest;

/**
 * Диспетчеры
 */
class Dispatchers extends Employees {
    
    public static function getListDispatchers() {
        
        $query = (new \yii\db\Query)
                ->select('e.employee_id as id, '
                        . 'e.employee_surname as surname, e.employee_name as name, e.employee_second_name as second_name,'
                        . 'd.department_name as department_name, p.post_name as post_name,'
                        . 'u.user_login as login, u.last_login as last_login,'
                        . 'au.item_name as role')
                ->from('employees as e')
                ->join('LEFT JOIN', 'user as u', 'e.employee_id = u.user_employee_id')
                ->join('LEFT JOIN', 'departments as d', 'e.employee_department_id = d.department_id')
                ->join('LEFT JOIN', 'posts as p', 'e.employee_posts_id = p.post_id')
                ->join('LEFT JOIN','auth_assignment as au','au.user_id = u.user_id')
                ->where(['au.item_name' => 'dispatcher'])
                ->orderBy(['e.employee_surname' => SORT_ASC]);
        
        return $query;
        
    }
    
    /*
     * Поиск не закрытых:
     *      заявок 
     *      заявок за платные услуги
     * 
     * true Имеются не закрытые заявки
     * false Все заявки закрыты
     */
    public static function findRequestsNotClose($dispatcher_id) {
        
        $requests = Requests::find()
                ->andWhere(['requests_dispatcher_id' => $dispatcher_id])
                ->andWhere(['!=', 'status', StatusRequest::STATUS_CLOSE])
                ->asArray()
                ->count();
        
        $paid_services = PaidServices::find()
                ->andWhere(['services_dispatcher_id' => $dispatcher_id])
                ->andWhere(['!=', 'status', StatusRequest::STATUS_CLOSE])
                ->asArray()
                ->count();
        
        if ($requests > 0 || $paid_services > 0) {
            return true;
        }
        
        return false;
        
    }

    
}
