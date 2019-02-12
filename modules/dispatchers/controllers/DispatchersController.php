<?php

    namespace app\modules\dispatchers\controllers;
    use app\modules\dispatchers\controllers\AppDispatchersController;

/**
 * Диспетчеры
 */
class DispatchersController extends AppDispatchersController {
    
    /*
     * Диспетчеры, главная страница
     */
    public function actionIndex() {
        
        return $this->render('index');
        
    }
    
}
