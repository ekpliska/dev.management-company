<?php

    namespace app\modules\dispatchers\widgets;
    use Yii;
    use yii\base\Widget;
    use app\models\Notifications as ModelNotifications;

/**
 * Виджет уведомлений
 */
class Notifications extends Widget {
    
    public $_notifications;

    public function init() {
        
        $this->_notifications = ModelNotifications::find()
                ->where(['user_uid' => Yii::$app->user->id])
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(7)
                ->all();
        
        parent::init();
    }
    
    public function run() {
        
        return $this->render('notifications/default', [
            'notifications_lists' => $this->_notifications,
        ]);
        
    }
    
}
