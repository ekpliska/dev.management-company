<?php

    namespace app\modules\clients\controllers;
    use yii\web\Controller;

/**
 * Description of PaidServicesController
 *
 * @author Ekaterina
 */
class PaidServicesController extends Controller {
    
    public function actionOrderServices() {
        return $this->render('order-services');
    }
    
}
