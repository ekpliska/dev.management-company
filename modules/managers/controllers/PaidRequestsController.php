<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\helpers\ArrayHelper;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\CategoryServices;
    use app\models\Services;
    use app\modules\managers\models\form\PaidRequestForm;
    use app\modules\managers\models\PaidServices;
    use app\modules\managers\models\searchForm\searchPaidRequests;
    use app\modules\managers\models\Specialists;
    use app\helpers\FormatFullNameUser;
    use app\models\StatusRequest;
    
/**
 * Заявки
 */
class PaidRequestsController extends AppManagersController {
    
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['PaidRequestsView']
                    ],
                    [
                        'actions' => ['view-paid-request',
                            'create-paid-request', 
                            'validation-form',
                            'switch-status-request',
                            'choose-dispatcher',
                            'choose-specialist',
                            'confirm-delete-request',
                            'edit-paid-request'],
                        'allow' => true,
                        'roles' => ['PaidRequestsEdit']
                    ],
                ],
            ],
        ];
    }
    
    public $type_request = [
        'requests',
        'paid-requests',
    ];
    
    public function actionIndex() {
        
        // Загружаем модель добавления заявки
        $model = new PaidRequestForm();
        
        // Формируем массив для Категорий услуг
        $servise_category = CategoryServices::getCategoryNameArray();
        // Массив для наименования услуг
        $servise_name = [];
        // Массив квартир (для привязки заявки к лицевому счету)
        $flat = [];
        
        // Загружаем модель поиска
        $search_model = new searchPaidRequests();
        
        // Загружаем список всех спициалистов
        $specialist_lists = ArrayHelper::map(Specialists::getListSpecialists()->all(), 'id', function ($data) {
            return FormatFullNameUser::nameEmployee($data['surname'], $data['name'], $data['second_name']);
        });
        
        // Статусы заявок
        $status_list = StatusRequest::getStatusNameArray();
        
        // Загружаем список услуг для формы поиска
        $name_services = Services::getServicesNameArray();
        
        $paid_requests = $search_model->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'model' => $model,
            'search_model' => $search_model,
            'servise_category' => $servise_category,
            'servise_name' => $servise_name,
            'flat' => $flat,
            'specialist_lists' => $specialist_lists,
            'name_services' => $name_services,
            'paid_requests' => $paid_requests,
            'status_list' => $status_list,
        ]);
    }
    
    /*
     * Просмотр и редактирование заявки, на платную услугу
     */
    public function actionViewPaidRequest($request_number) {
        
        $paid_request = PaidServices::findRequestByIdent($request_number);
        
        if (!isset($paid_request) && $paid_request == null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        return $this->render('view-paid-request', [
            'paid_request' => $paid_request,
        ]);
    }
    
    /*
     * Метод сохранения созданной заявки на платную услугу
     */
    public function actionCreatePaidRequest() {
        
        $model = new PaidRequestForm();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $number = $model->save();
            return $this->redirect(['view-paid-request', 'request_number' => $number]);
        }
    }    
    
    /*
     * Валидация формы в модальном окне "Создать заявку"
     * Валидация формы в модальном окне "Создать заявку на платную услугу"
     */
    public function actionValidationForm($form) {
        
        if ($form == 'paid-request') {
            $model = new PaidRequestForm();
        } elseif ($form == 'edit-paid-request') {
            $model = new PaidServices();
            $model->scenario = PaidServices::SCENARIO_EDIT_REQUEST;
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
    }
    
    /*
     * Форма редактирования заявок на платные услуги
     */
    public function actionEditPaidRequest($request_id) {
        
        $model = PaidServices::findOne($request_id);
        $model->scenario = PaidServices::SCENARIO_EDIT_REQUEST;
        
        $servise_category = CategoryServices::getCategoryNameArray();;
        $servise_name = Services::getPayServices($model->services_servise_category_id);
        $adress_list = $model->getUserAdress($model->services_phone);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('modal/edit-paid-request', [
                'model' => $model,
                'servise_category' => $servise_category,
                'servise_name' => ArrayHelper::map($servise_name, 'service_id', 'service_name'),
                'adress_list' => $adress_list,
            ]);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', ['message' => 'Информация по заявке была изменена']);
            return $this->redirect(Yii::$app->request->referrer);
        }
        
    } 
        
}
