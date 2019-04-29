<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use yii\helpers\ArrayHelper;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\modules\dispatchers\models\searchForm\searchRequests;
    use app\modules\dispatchers\models\searchForm\searchPaidRequests;
    use app\models\TypeRequests;
    use app\models\Services;
    use app\models\CategoryServices;
    use app\models\StatusRequest;
    use app\modules\dispatchers\models\Specialists;
    use app\helpers\FormatFullNameUser;
    
/**
 * Отчеты
 */
class ReportsController extends AppDispatchersController {
    
    /*
     * Главная страница
     */
    public function actionIndex($block = 'requests') {
        
        // Загружаем виды заявок        
        $type_requests = TypeRequests::getTypeNameArray();
        // Загружаем список услуг для формы поиска
        $name_services = Services::getServicesNameArray();
        // Формируем массив для Категорий услуг
        $servise_category = CategoryServices::getCategoryNameArray();
        // Статусы заявок
        $status_list = StatusRequest::getStatusNameArray();
        // Загружаем список всех спициалистов
        $specialist_lists = ArrayHelper::map(Specialists::getListSpecialists()->all(), 'id', function ($data) {
            return FormatFullNameUser::nameEmployee($data['surname'], $data['name'], $data['second_name']);
        });
        
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
            'type_requests' => $type_requests,
            'name_services' => $name_services,
            'servise_category' => $servise_category,
            'status_list' => $status_list,
            'specialist_lists' => $specialist_lists,
        ]);
        
    }
    
}
