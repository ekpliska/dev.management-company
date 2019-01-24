<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\CategoryServices;
    use app\models\Services;

/**
 * Конструктор заявок
 */
class DesignerRequestsController extends AppManagersController {
    
    public function actionIndex($section = 'requests') {
        
        $results = [];
        
        switch ($section) {
            case 'requests':
                $results = [];
                break;
            case 'paid-services':
                $results = [
                    'categories' => CategoryServices::getCategoryNameArray(),
                    'services' => Services::getServicesNameArray(),
                ];
                break;
        }
        
        return $this->render('index', [
            'section' => $section,
            'results' => $results,
        ]);
        
    }
    
}
