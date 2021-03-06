<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\TypeRequests;
    use app\models\CategoryServices;
    use app\models\Services;
    use app\modules\managers\models\form\ServiceForm;
    use app\models\Units;
    use app\models\RequestQuestions;

/**
 * Конструктор заявок
 */
class DesignerRequestsController extends AppManagersController {
    
    public $category_cookie;
    public $request_cookie;

    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'show-results',
                        ],
                        'allow' => true,
                        'roles' => ['DesignerView']
                    ],
                    [
                        'actions' => [
                            'validation-form', 
                            'create-record', 
                            'delete-record', 
                            'edit-service', 
                            'edit-question', 
                        ],
                        'allow' => true,
                        'roles' => ['DesignerEdit']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($section = 'requests') {
        
        $results = [];

        $model_request = new TypeRequests();
        $model_question = new RequestQuestions();
        $model_category = new CategoryServices();
        $model_service = new ServiceForm();
        
        switch ($section) {
            case 'requests':
                // Из куки получаем выбранную заявку
                $this->request_cookie = $this->actionReadCookies('choosing-request');
                $results = [
                    'requests' => TypeRequests::getTypeNameArray(),
                    'questions' => $this->request_cookie ? RequestQuestions::getAllQuestions($this->request_cookie) : null,
                ];
                break;
            case 'paid-services':
                // Из куки получаем выбранную категорию
                $this->category_cookie = $this->actionReadCookies('choosing-category');
                $results = [
                    'categories' => CategoryServices::getCategoryNameArray(),
                    'services' => $this->category_cookie ? Services::getPayServices($this->category_cookie) : null,
                    'units' => Units::getUnitsArray(),
                ];
                break;
        }
        
        return $this->render('index', [
            'section' => $section,
            'model_category' => $model_category,
            'model_service' => $model_service,
            'model_request' => $model_request,
            'model_question' => $model_question,
            'results' => $results,
        ]);
        
    }
    
    /*
     * Валидация форм
     */
    public function actionValidationForm($form) {
        
        switch ($form) {
            case 'new-category':
                $model = new CategoryServices();
                break;
            case 'new-service':
                $model = new ServiceForm();
                break;
            case 'edit-service-form':
                $model = new Services();
                break;
            case 'new-request':
                $model = new TypeRequests();
                break;
            case 'new-question':
                $model = new RequestQuestions();
                break;
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        
    }
    
    /*
     * Обработка форм
     */
    public function actionCreateRecord($form) {
        
        switch ($form) {
            case 'new-category':
                $model = new CategoryServices();
                $section = 'paid-services';
                break;
            case 'new-service':
                $model = new ServiceForm();
                $section = 'paid-services';
                break;
            case 'new-request':
                $model = new TypeRequests();
                $section = 'requests';
                break;
            case 'new-question':
                $model = new RequestQuestions();
                $section = 'requests';
                break;
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($form == 'new-service') {
                $file = UploadedFile::getInstance($model, 'service_image');
                $model->service_image = $file;
                $record = $model->save($file);
                return $this->redirect(['index', 'section' => $section]);
            }
            $record = $model->save();
            return $this->redirect(['index', 'section' => $section]);
        }
        
    }
    
    public function actionShowResults ($type_record, $record_id) {
        
        $this->setCookieChooseHouse($type_record, $record_id);
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            switch ($type_record) {
                case 'category':
                    $results = Services::getPayServices($record_id);
                    break;
                case 'request':
                    $results = RequestQuestions::getAllQuestions($record_id);
                    break;
            }
            
            
            $data = $this->renderAjax("data/data-{$type_record}", ['results' => $results]);
            return ['success' => true, 'data' => $data];
        }
        
        return ['success' => false];
    }
    
    public function actionDeleteRecord ($type, $record_id) {
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            switch ($type) {
                case 'category':
                    $result = CategoryServices::findOne($record_id);                    
                    break;
                case 'service':
                    $result = Services::findOne($record_id);                    
                    break;
                case 'request':
                    $result = TypeRequests::findOne($record_id);                    
                    break;
                case 'question':
                    $result = RequestQuestions::findOne($record_id);                    
                    break;
                default:
                    Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления. Обновите страницу и повторите действие еще раз']);
                    return $this->redirect(Yii::$app->request->referrer);
            }
            
            if (!$result->delete()) {
                Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления. Обновите страницу и повторите действие еще раз']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            
            Yii::$app->session->setFlash('success', ['message' => 'Выбранная запись была успешно удалена']);
            return $this->redirect(Yii::$app->request->referrer);
            
        }
    }
    
    
    /*
     * Загрузка модального окна на редактирование услуги
     * Сохранение данных
     */
    public function actionEditService($service_id) {
        
        $model = Services::findOne($service_id);
        $categories_list = CategoryServices::getCategoryNameArray();
        $units = Units::getUnitsArray();
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('modal/edit-service', [
                'model' => $model,
                'categories_list' => $categories_list,
                'units' => $units,
            ]);
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'image');
//            var_dump($file);die();
            $model->uploadImage($file);
            return $this->redirect(['index', 'section' => 'paid-services']);
        }
        
    }

    /*
     * Загрузка модального окна на редактирование вопроса
     * Сохранение данных
     */
    public function actionEditQuestion ($question_id) {
        
        $model = RequestQuestions::findOne($question_id);
        $type_requests = TypeRequests::getTypeNameArray();
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('modal/edit-question', [
                'model' => $model,
                'type_requests' => $type_requests,
            ]);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'section' => 'requests']);
        }        
        
    }
    
    
    /*
     * Установка куки выбранной категории
     */
    public function setCookieChooseHouse($type_record, $value) {
        
        $cookies = Yii::$app->response->cookies;
        
        $cookies->add(new \yii\web\Cookie ([
            'name' => "choosing-{$type_record}",
            'value' => $value,
            'expire' => time() + 60*60*24*7,
        ]));
        
    }
}
