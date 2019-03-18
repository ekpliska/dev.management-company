<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\helpers\ArrayHelper;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\models\PaidServices;
    use app\models\CommentsToRequest;
    use app\models\Image;
    use app\models\StatusRequest;
    use app\modules\dispatchers\models\RequestsList;
    use app\modules\dispatchers\models\PaidServicesList;
    use app\modules\dispatchers\models\searchForm\searchRequests;
    use app\modules\dispatchers\models\searchForm\searchPaidRequests;
    use app\models\TypeRequests;
    use app\models\Services;
    use app\models\PersonalAccount;
    use app\modules\dispatchers\models\Specialists;
    use app\helpers\FormatFullNameUser;
    use app\modules\dispatchers\models\form\RequestForm;
    use app\modules\dispatchers\models\form\PaidRequestForm;
    use app\models\CategoryServices;

/**
 * Заявки, Платные услуги
 */
class RequestsController extends AppDispatchersController {
    
    public function actionIndex($block = 'requests') {
        
        // Загружаем виды заявок        
        $type_requests = TypeRequests::getTypeNameArray();
        // Загружаем список услуг для формы поиска
        $name_services = Services::getServicesNameArray();
        // Формируем массив для Категорий услуг
        $servise_category = CategoryServices::getCategoryNameArray();
        
        // Загружаем список всех спициалистов
        $specialist_lists = ArrayHelper::map(Specialists::getListSpecialists()->all(), 'id', function ($data) {
            return FormatFullNameUser::nameEmployee($data['surname'], $data['name'], $data['second_name']);
        });
        
        switch ($block) {
            case 'requests':
                // Загружаем модель создания новой заявки
                $model = new RequestForm();
                // Загружаем модель поиска
                $search_model = new searchRequests();
                $results = $search_model->search(Yii::$app->request->queryParams);
                break;
            case 'paid-requests':
                // Загружаем модель для загрузки заявки на платную услугу
                $model = new PaidRequestForm();
                // Загружаем модель поиска
                $search_model = new searchPaidRequests();
                $results = $search_model->search(Yii::$app->request->queryParams);
                break;
        }
        
        return $this->render('index', [
            'block' => $block,
            'type_requests' => $type_requests,
            'name_services' => $name_services,
            'specialist_lists' => $specialist_lists,
            'search_model' => $search_model,
            'results' => $results,
            'model' => $model,
            'flat' => [],
            'servise_category' => $servise_category,
        ]);
        
    }
    
    public function actionViewRequest($request_number) {
        
        $request = RequestsList::findRequestToIdent($request_number);
        
        if ($request == null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $model_comment = new CommentsToRequest([
            'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
        ]);

        /*
         * Загружаем модель для добавления комментрария к задаче
         * Pjax
         */
        if (Yii::$app->request->isPjax) {
            $model_comment = new CommentsToRequest([
                'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
            ]);        
            if ($model_comment->load(Yii::$app->request->post())) {
                $model_comment->sendComments($request['requests_id']);
            }
        }
        
        $comments_find = CommentsToRequest::findCommentsById($request['requests_id']);
        
        // Получаем прикрепленные к заявке файлы
        $images = Image::find()->andWhere(['itemId' => $request['requests_id']])->all();
        
        
        return $this->render('view-request', [
            'request' => $request,
            'model_comment' => $model_comment,
            'comments_find' => $comments_find,
            'all_images' => $images,
        ]);
        
    }
    
    /*
     * Просмотр и редактирование заявки, на платную услугу
     */
    public function actionViewPaidRequest($request_number) {
        
        $paid_request = PaidServicesList::findRequestByIdent($request_number);
        
        if (!isset($paid_request) && $paid_request == null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        return $this->render('view-paid-request', [
            'paid_request' => $paid_request,
        ]);
    }
    
    /*
     * Назначение специалиста для Заявок и Заявок на платные услуги
     */
    public function actionChooseSpecialist() {
        
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $request_id = Yii::$app->request->post('requestId');
        $specialist_id = Yii::$app->request->post('specialistId');
        $type_request = Yii::$app->request->post('typeRequest');
        
        if (Yii::$app->request->isAjax) {
            
            switch ($type_request) {
                case 'requests':
                    $result = RequestsList::findByID($request_id);
                    $result->chooseSpecialist($specialist_id);
                    break;
                case 'paid-requests':
                    $result = PaidServicesList::findOne($request_id);
                    $result->chooseSpecialist($specialist_id);
                    break;
                default:
                    break;
            }
            
            if (!$result) {
                Yii::$app->session->setFlash('error', ['message' => 'Возникла внутренная ошибка. Обновите страницу и повторите действие заново']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            
            Yii::$app->session->setFlash('success', ['message' => 'Изменения в теле заявки были успешно сохранены']);
            return $this->redirect(Yii::$app->request->referrer);
                        
        }
        
    }
    
    /*
     * Запрос на установку статуса "Отлонено"
     */
    public function actionConfirmRejectRequest() {
        
        $request_id = Yii::$app->request->post('requestID');
        $request_status = Yii::$app->request->post('requestStatus');
        $request_type = Yii::$app->request->post('requestType');
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        // Проверяем существование пришедшего статуса
        if (!ArrayHelper::keyExists($request_status, StatusRequest::getStatusNameArray()) || !is_numeric($request_id)) {
            return ['success' => false];
        }
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            
            switch ($request_type) {
                case 'requests':
                    $request = RequestsList::findOne($request_id);
                    break;
                case 'paid-requests':
                    $request = PaidServices::findOne($request_id);
                    break;
                default:
                    return ['success' => false];
            }
            if (!$request->setSatusRequest($request_status)) {
                return ['success' => false];
            }
            return [
                'success' => true,
                'status_name' => StatusRequest::statusName($request_status),
            ];
        }
        
        return ['success' => true];
    }
    
    /*
     * Метод сохранения созданной заявки
     */
    public function actionCreateRequest() {
        
        $model = new RequestForm();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $number = $model->save();
            return $this->redirect(['view-request', 'request_number' => $number]);
        }
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
     * Валидация форм
     */
    public function actionValidationForm($form) {
        
        if ($form == 'new-request') {
            $model = new RequestForm();
        } elseif ($form == 'paid-request') {
            $model = new PaidRequestForm();
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
    }
    
    /*
     * Поиск адресов по введенному номеру телефона пользователя
     */
    public function actionShowHouses($phone) {
        
        $model = new RequestForm();
        $client_id = $model->findClientPhone($phone);
        
        $house_list = PersonalAccount::find()
                ->select(['account_id', 'houses_gis_adress', 'houses_number', 'flats_number'])
                ->joinWith(['flat', 'flat.house'])
                ->andWhere(['personal_clients_id' => $client_id])
                ->orWhere(['personal_rent_id' => $client_id])
                ->asArray()
                ->all();
        
        if (!empty($client_id)) {
            foreach ($house_list as $house) {
                $full_adress = 
                        $house['houses_gis_adress'] . ', д. ' .
                        $house['houses_number'] . ', кв. ' .
                        $house['flats_number'];
                echo '<option value="' . $house['account_id'] . '">' . $full_adress . '</option>';
            }
        } else {
            echo '<option>Адрес не найден</option>';
        }        
        
    }
    
    /*
     * Поиск наименование услуги по выбранной категории
     */
    public function actionShowNameService($categoryId) {
        
        $category_list = CategoryServices::find()
                ->andWhere(['category_id' => $categoryId])
                ->asArray()
                ->count();
        
        $service_list = Services::find()
                ->andWhere(['service_category_id' => $categoryId])
                ->asArray()
                ->all();
        
        if ($category_list > 0) {
            foreach ($service_list as $service) {
                echo '<option value="' . $service['service_id'] . '">' . $service['service_name'] . '</option>';
            }
        } else {
            echo '<option>-</option>';
        }
        
    }
    
    /*
     * Запрос на отключение чата у заявки
     */
    public function actionConfirmCloseChat($request_id) {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            if (!RequestsList::closeChat($request_id)) {
                return ['success' => false];
            }
            
            return ['success' => true];
        }
        
    }
}
