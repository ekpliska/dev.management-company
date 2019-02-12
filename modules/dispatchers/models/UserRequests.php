<?php

    namespace app\modules\dispatchers\models;
    use app\models\Clients;
    use app\models\StatusRequest;
    
/* 
 * Список пользовательских заявок
 */

class UserRequests extends Clients {
    
    public static function getRequestsByUser() {
        
        $query = self::find()
                ->joinWith(['user u', 'personalAccount pa', 'personalAccount.request rq'])
                ->where(['!=', 'rq.status', StatusRequest::STATUS_CLOSE])
                ->asArray()
                ->all();
        
        return $query;
    }
    
}

