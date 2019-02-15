<?php

    namespace app\modules\dispatchers\controllers;
    use app\modules\dispatchers\controllers\AppDispatchersController;

/**
 * Отчеты
 */
class ReportsController extends AppDispatchersController {
    
    /*
     * Главная страница
     */
    public function actionIndex() {
        
        return $this->render('index');
        
    }
    
}
