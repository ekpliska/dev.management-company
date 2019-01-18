<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\helpers\ArrayHelper;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\CategoryServices;
    use app\modules\managers\models\form\RequestForm;
    use app\modules\managers\models\form\PaidRequestForm;
    use app\modules\managers\models\Requests;
    use app\modules\managers\models\PaidServices;

/**
 * Заявки
 */
class PaidRequestsController extends AppManagersController {
    
    public $type_request = [
        'requests',
        'paid-requests',
    ];
    
    public function actionIndex() {
        
        $model = new PaidRequestForm();
        $servise_category = CategoryServices::getCategoryNameArray();
        $servise_name = [];
        $flat = [];
        
        $paid_requests = new ActiveDataProvider([
            'query' => PaidServices::getAllPaidRequests(),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15,
            ],
        ]);
        
        return $this->render('index', [
            'model' => $model,
            'servise_category' => $servise_category,
            'servise_name' => $servise_name,
            'flat' => $flat,
            'paid_requests' => $paid_requests,
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
            'model_comment' => $model_comment,
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
        
        if ($form == 'new-request') {
            $model = new RequestForm();
        } elseif ($form == 'paid-request') {
            $model = new PaidRequestForm();
        } elseif ($form == 'edit-request') {
            $model = new Requests();
            $model->scenario = Requests::SCENARIO_EDIT_REQUEST;
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
    }
    
    /*
     * Метод переключения статуса для заявки
     */
    public function actionSwitchStatusRequest() {
        
        $status = Yii::$app->request->post('statusId');
        $request_id = Yii::$app->request->post('requestId');
        $type_request = Yii::$app->request->post('typeRequest');
        
        // Если параметр "тип заявки" пришел не верный отправляем на главную страницу
        if (ArrayHelper::keyExists($type_request, $this->type_request)) {
            return $this->goHome();
        }
        
        if (Yii::$app->request->isAjax) {
            
            switch ($type_request) {
                case 'requests':
                    $request = Requests::findOne($request_id);
                    $request->switchStatus($status);                    
                    break;
                case 'paid-requests':
                    $request = PaidServices::findOne($request_id);
                    $request->switchStatus($status);
                    break;
                default:
                    return ['success' => false];
            }
            
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true, 'status' => $status];
        }
        
        return ['success' => false];
    }
    
    /*
     * Назначение диспетчера для Заявок и Заявок на платные услуги
     */
    public function actionChooseDispatcher() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $request_id = Yii::$app->request->post('requestId');
        $dispatcher_id = Yii::$app->request->post('dispatcherId');
        $type_request = Yii::$app->request->post('typeRequest');
        
        // Если параметр "тип заявки" пришел не верный отправляем на главную страницу
        if (ArrayHelper::keyExists($type_request, $this->type_request)) {
            return $this->goHome();
        }
        
        if (Yii::$app->request->isAjax) {
            switch ($type_request) {
                case 'requests':
                    $request = Requests::findByID($request_id);
                    $request->chooseDispatcher($dispatcher_id);
                    return ['success' => true];
                    break;
                
                case 'paid-requests':
                    $paid_request = PaidServices::findOne($request_id);
                    $paid_request->chooseDispatcher($dispatcher_id);
                    return ['success' => true];
                    break;
                default:
                    return ['success' => false];
            }
            return ['success' => true];
        }
        
        return ['success' => false];
        
    }
    
    /*
     * Назначение специалиста для Заявок и Заявок на платные услуги
     */
    public function actionChooseSpecialist() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $request_id = Yii::$app->request->post('requestId');
        $specialist_id = Yii::$app->request->post('specialistId');
        $type_request = Yii::$app->request->post('typeRequest');
        
        // Если параметр "тип заявки" пришел не верный отправляем на главную страницу
        if (ArrayHelper::keyExists($type_request, $this->type_request)) {
            return $this->goHome();
        }        
        
        if (Yii::$app->request->isAjax) {
            
            switch ($type_request) {
                case 'requests':
                    $request = Requests::findByID($request_id);
                    $request->chooseSpecialist($specialist_id);
                    return ['success' => true];
                    break;
                case 'paid-requests':
                    $paid_request = PaidServices::findOne($request_id);
                    $paid_request->chooseSpecialist($specialist_id);
                    return ['success' => true];
                    break;
                default:
                    return ['success' => false];                    
                    break;
            }
        }
        
        return ['success' => false];
        
    }
    
    /*
     * Запрос на удаление заявки
     */
    public function actionConfirmDeleteRequest($type, $request_id) {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            switch ($type) {
                case 'requests':
                    $request = Requests::findByID($request_id);
                    break;
                case 'paid-requests':
                    $request = PaidServices::findByID($request_id);
                    break;
                default:
                    return ['success' => false];
            }
            
            if (!$request->delete()) {
                Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз']);
            }
            
            Yii::$app->session->setFlash('success', ['message' => 'Заявка была успешно удалена']);
            return $this->redirect('index');
        }
        
    }
    
}
