<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;

/**
 * Description of ServicesRatesController
 *
 * @author Ekaterina
 */
class RequestsController extends AppManagersController {
    
    public function actionIndex() {
        return $this->render('index');
    }
    
}
