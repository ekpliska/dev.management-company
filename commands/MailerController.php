<?php

    namespace app\commands;
    use Yii;
    use yii\console\Controller;
    use app\models\SendSubscribers;

/**
 * SendSubscribers
 */
class MailerController extends Controller {
    
    public $defaultAction = 'send';
    
    public function actionSend() {
        
        $subscribers = new SendSubscribers();
        var_dump($subscribers->send());
        die();
        return true;
        
    }
    
}
