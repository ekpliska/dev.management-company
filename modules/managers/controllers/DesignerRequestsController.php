<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;

/**
 * Конструктор заявок
 */
class DesignerRequestsController extends AppManagersController {
    
    public function actionIndex($section = 'requests') {
        
        return $this->render('index', [
            'section' => $section,
        ]);
        
    }
    
}
