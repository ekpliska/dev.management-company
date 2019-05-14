<?php

    namespace app\commands;
    use yii\console\Controller;
    use app\models\SendSubscribers;

/**
 * SendSubscribers
 */
class MailerController extends Controller {
    
    public function actionSend() {
        
        $subscribers = new SendSubscribers();
        $subscribers->send();
        return true;
        
    }
    
}
