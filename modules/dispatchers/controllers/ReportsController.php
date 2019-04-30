<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use yii\helpers\ArrayHelper;
    use yii\web\NotFoundHttpException;
    use kartik\mpdf\Pdf;
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
        
        if (!Yii::$app->session->isActive) {
            Yii::$app->session->open();
        }
        
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
                Yii::$app->session['session_query'] = Yii::$app->request->queryParams;
                break;
            case 'paid-requests':
                // Загружаем модель поиска
                $search_model = new searchPaidRequests();
                $results = $search_model->search(Yii::$app->request->queryParams);
                Yii::$app->session['session_query'] = Yii::$app->request->queryParams;
                break;
            default:
                throw new NotFoundHttpException('Вы обратились к несуществующей странице');
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
    
    /*
     * Формирование выборки фильтра в PDF
     */
    public function actionCreateReport($block) {
        
        $session_query = Yii::$app->session->has('session_query') ? Yii::$app->session['session_query'] : Yii::$app->request->queryParams;
        
        switch ($block) {
            case 'requests':
                $search_model = new searchRequests();
                $results = $search_model->search($session_query);
                $title = 'Заявки';
                break;
            case 'paid-requests':
                $search_model = new searchPaidRequests();
                $results = $search_model->search($session_query);
                $title = 'Заявки на платные услуги';
                break;
            default:
                throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $content = $this->renderPartial("data/grid_{$block}", [
            'search_model' => $search_model,
            'results' => $results,
        ]);
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, 
            'format' => Pdf::FORMAT_A4, 
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            'destination' => Pdf::DEST_BROWSER, 
            'defaultFontSize' => 12,
            'content' => $content,
            'options' => [
                'title' => 'Отчет PDF ',
            ],
            'methods' => [ 
                'SetHeader'=>[$title], 
                'SetFooter'=>['стр. ' . '{PAGENO}'],
            ]
        ]);
    
        return $pdf->render(); 
        
    }
        
}
