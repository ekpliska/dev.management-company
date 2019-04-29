<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\modules\dispatchers\models\searchForm\searchRequests;
    use app\modules\dispatchers\models\searchForm\searchPaidRequests;

/**
 * Отчеты
 */
class ReportsController extends AppDispatchersController {
    
    /*
     * Главная страница
     */
    public function actionIndex($block = 'requests') {
        
        switch ($block) {
            case 'requests':
                // Загружаем модель поиска
                $search_model = new searchRequests();
                $results = $search_model->search(Yii::$app->request->queryParams);
                break;
            case 'paid-requests':
                // Загружаем модель поиска
                $search_model = new searchPaidRequests();
                $results = $search_model->search(Yii::$app->request->queryParams);
                break;
        }
        
        return $this->render('index', [            
            'block' => $block,
            'search_model' => $search_model,
            'results' => $results,
        ]);
        
    }
    
}
