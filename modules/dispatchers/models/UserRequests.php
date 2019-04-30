<?php

    namespace app\modules\dispatchers\models;
    use Yii;
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
        
        $result = self::find()
                ->joinWith([
                    'user u', 
                    'personalAccount pa', 
                    'personalAccount.request rq' => function($query) {
                        $query->with('image');
                        $query->where(['rq.requests_dispatcher_id' => Yii::$app->profileDispatcher->employeeID]);
                        $query->where(['!=', 'rq.status', StatusRequest::STATUS_CLOSE]);
                        $query->orderBy(['rq.status' => SORT_ASC]);
                    }, 
                    'personalAccount.flat fl', 
                    'personalAccount.flat.house hs'])
                ->asArray()
                ->all();
                    
        return $result;
    }

    /*
     * Получем список всех не завершенных заявок на платные услуги 
     * для главной страницы модуля "Диспетчеры"
     */
    public static function getPaidRequestsByUser() {
        
        $result = self::find()
                ->joinWith([
                    'user u', 
                    'personalAccount pa', 
                    'personalAccount.paidRequest ps' => function($query) {
                        $query->andWhere(['ps.services_dispatcher_id' => Yii::$app->profileDispatcher->employeeID]);
                        $query->orWhere(['ps.status' => StatusRequest::STATUS_NEW]);
                        $query->andWhere(['!=', 'ps.status', StatusRequest::STATUS_CLOSE]);
                        $query->orderBy(['ps.status' => SORT_ASC]);
                    }, 
                    'personalAccount.flat fl', 
                    'personalAccount.flat.house hs'])
                ->asArray()
                ->all();
        
        return $result;
    }

    /*
     * Получем список всех не завершенных заявок для конкретного пользователя
     */
    public static function getRequestsByUserID($user_id) {
        
        $query = self::find()
                ->joinWith([
                    'user u', 
                    'personalAccount pa', 
                    'personalAccount.request rq' => function($query) {
                        $query->with('image');
                        $query->where(['rq.requests_dispatcher_id' => Yii::$app->profileDispatcher->employeeID]);
                        $query->where(['!=', 'rq.status', StatusRequest::STATUS_CLOSE]);
                        $query->orderBy(['rq.status' => SORT_ASC]);
                    }, 
                    'personalAccount.flat fl', 
                    'personalAccount.flat.house hs'])
                ->where(['u.user_id' => $user_id])
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
                    'personalAccount.paidRequest ps' => function($query) {
                        $query->andWhere(['ps.services_dispatcher_id' => Yii::$app->profileDispatcher->employeeID]);
                        $query->orWhere(['ps.status' => StatusRequest::STATUS_NEW]);
                        $query->andWhere(['!=', 'ps.status', StatusRequest::STATUS_CLOSE]);
                        $query->orderBy(['ps.status' => SORT_ASC]);
                    }, 
                    'personalAccount.flat fl', 
                    'personalAccount.flat.house hs'])
                ->where(['u.user_id' => $user_id])
                ->asArray()
                ->all();
        
        return $query;
    }
    
}

