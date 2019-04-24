<?php

    namespace app\modules\api\v1\models\notification;
    use Yii;
    use app\models\Notifications;

/**
 * Уведомления
 */
class NotificationLists extends Notifications {
    
    /*
     * Получить список уведомления для текущего пользователя
     */
    public static function getNotifiactions() {
        
        $list = self::find()
                ->select(['id', 'value_2 as type_note', 'message', 'value_3 as record_id'])
                ->where(['user_uid' => Yii::$app->user->getId()])
                ->limit(7)
                ->asArray()
                ->all();
        
        return $list;
        
    }
    
}
