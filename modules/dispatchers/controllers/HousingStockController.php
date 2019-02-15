<?php

    namespace app\modules\dispatchers\controllers;
    use app\modules\dispatchers\controllers\AppDispatchersController;

/**
 * Жилищный фонд
 */
class HousingStockController extends AppDispatchersController {
    
    public function actionIndex() {
        
        return $this->render('index');
        
    }
    
}
