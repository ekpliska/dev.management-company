<?php

    namespace app\modules\dispatchers\controllers;
    use app\modules\dispatchers\controllers\AppDispatchersController;

/**
 * Заявки, Платные услуги
 */
class RequestsController extends AppDispatchersController {
    
    public function actionIndex($block = 'requests') {
        
        return $this->render('index');
        
    }
    
}
