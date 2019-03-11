<?php

    namespace app\modules\api\v1\models;
    use app\models\Requests as BaseRequests;
    use app\models\StatusRequest;

/**
 * Заявки собсвеника
 */
class Requests extends BaseRequests {
    
    public static function getAllRequests($account_number) {
        
        $results = [];
        
        foreach (StatusRequest::getUserRequests() as $key => $status) {
            
            $requests = self::find()
                ->select(['requests_id', 'type_requests_name', 'status'])
                ->joinWith([
                    'typeRequest',
                    'personalAccount' => function ($query) use ($account_number) {
                        $query->onCondition(['account_number' => $account_number]);
                    }])
                ->where(['status' => $key])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->all();
                   
            $results[$status] = $requests;
        }            
                    
        return $results;
        
    }
    
}
