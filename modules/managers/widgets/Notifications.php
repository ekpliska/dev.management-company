<?php

    namespace app\modules\managers\widgets;
    use Yii;
    use yii\base\Widget;
    use app\models\Notifications as ModelNotifications;
    use app\models\Requests;
    use app\models\PaidServices;

/**
 * Виджет уведомлений
 */
class Notifications extends Widget {
    
    public $_notifications;

    public function init() {
        
        $this->getRequestList();
        $this->getPaidReqestList();
        
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
    
    /*
     * Не рассмотренный новые заявки
     */
    private function getRequestList() {
        return Requests::getMissedListRequest();
    }
    
    /*
     * Не рассмотренные заявки на платные услуги
     */
    private function getPaidReqestList() {
        return PaidServices::getMissedListPaidRequest();
    }
    
}
