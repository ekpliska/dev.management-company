<?php

    namespace app\modules\clients\widgets;
    use Yii;
    use yii\base\Widget;
    use app\models\Notifications;

/**
 * Профиль пользователя из навигационного меню
 */
class UserInfo extends Widget {
    
    public $_notifications;
    
    
    public function init() {
        
        $this->_notifications = Notifications::find()
                ->where(['user_uid' => Yii::$app->user->id])
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(7)
                ->all();
        
        parent::init();        
        
    }

    public function run() {
        
        return $this->render('userinfo/profile', [
            'notifications_lists' => $this->_notifications,
        ]);
        
    }
    
}
