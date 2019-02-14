<?php

    namespace app\modules\dispatchers\models;
    use app\models\Clients;
    use app\models\StatusRequest;
    
/* 
 * Список пользовательских заявок
 */

class UserRequests extends Clients {
    
    /*
     * Получем список всех не завершенных заявок 
     * для главной страницы модуля "Диспетчеры"
     */
    public static function getRequestsByUser() {
        
        $query = self::find()
                ->joinWith([
                    'user u', 
                    'personalAccount pa', 
                    'personalAccount.request rq', 
                    'personalAccount.request.image i', 
                    'personalAccount.flat fl', 
                    'personalAccount.flat.house hs'])
                ->where(['!=', 'rq.status', StatusRequest::STATUS_CLOSE])
                ->orderBy(['rq.created_at' => SORT_ASC])
                ->asArray()
                ->all();
        
        return $query;
    }

    /*
     * Получем список всех не завершенных заявок на платные услуги 
     * для главной страницы модуля "Диспетчеры"
     */
    public static function getPaidRequestsByUser() {
        
        $query = self::find()
                ->joinWith([
                    'user u', 
                    'personalAccount pa', 
                    'personalAccount.paidRequest ps', 
                    'personalAccount.flat fl', 
                    'personalAccount.flat.house hs'])
                ->where(['!=', 'ps.status', StatusRequest::STATUS_CLOSE])
                ->orderBy(['ps.created_at' => SORT_ASC])
                ->asArray()
                ->all();
        
        return $query;
    }

    /*
     * Получем список всех не завершенных заявок для конкретного пользователя
     */
    public static function getRequestsByUserID($user_id) {
        
        $query = self::find()
                ->joinWith([
                    'user u', 
                    'personalAccount pa', 
                    'personalAccount.request rq', 
                    'personalAccount.request.image i', 
                    'personalAccount.flat fl', 
                    'personalAccount.flat.house hs'])
                ->where(['!=', 'rq.status', StatusRequest::STATUS_CLOSE])
                ->andWhere(['u.user_id' => $user_id])
                ->orderBy(['rq.created_at' => SORT_ASC])
                ->asArray()
                ->all();
        
        return $query;
    }

    /*
     * Получем список всех не завершенных заявок на платые услуги для конкретного пользователя
     */
    public static function getPaidRequestsByUserID($user_id) {
        
        $query = self::find()
                ->joinWith([
                    'user u', 
                    'personalAccount pa', 
                    'personalAccount.paidRequest ps', 
                    'personalAccount.flat fl', 
                    'personalAccount.flat.house hs'])
                ->where(['!=', 'ps.status', StatusRequest::STATUS_CLOSE])
                ->andWhere(['u.user_id' => $user_id])
                ->orderBy(['ps.created_at' => SORT_ASC])
                ->asArray()
                ->all();
        
        return $query;
    }
    
}

