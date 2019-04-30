<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use yii\data\Pagination;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\modules\dispatchers\models\UserRequests;
    use app\modules\dispatchers\models\News;
    use app\modules\dispatchers\models\form\RequestForm;
    use app\modules\dispatchers\models\form\PaidRequestForm;
    use app\models\TypeRequests;
    use app\models\CategoryServices;
    use app\models\Services;
    use app\models\PersonalAccount;
    use app\models\StatusRequest;

/**
 * Диспетчеры
 */
class DispatchersController extends AppDispatchersController {
    
    public function actionError() {
        
        $exception = Yii::$app->errorHandler->exception;
        $exception->statusCode == '404' ? $this->view->title = "Ошибка 404" : '';
        $exception->statusCode == '403' ? $this->view->title = "Доступ запрещён" : '';
        $exception->statusCode == '500' ? $this->view->title = "Внутренняя ошибка сервера" : '';
        
        return $this->render('error', ['message' => $exception->getMessage()]);
        
    }
    
    /*
     * Диспетчеры, главная страница
     */
    public function actionIndex($block = 'requests') {
        
        // Блок новостей
        $news_lists = News::getNewsList($count_news = 7);
        // Тип заявок
        $type_requests = TypeRequests::getTypeNameArray();
        // Формируем массив для Категорий услуг
        $servise_category = CategoryServices::getCategoryNameArray();
        // Массив для наименования услуг
        $servise_name = [];        
        // Жилой массив
        $flat = [];
        // Статусы заявок
        $status_list = StatusRequest::getStatusNameArray();
        
        switch ($block) {
            case 'requests':
                $model = new RequestForm();
                $user_lists = UserRequests::getRequestsByUser();
                break;
            case 'paid-requests':
                $model = new PaidRequestForm();
                $user_lists = UserRequests::getPaidRequestsByUser();;
                break;
        }
        
        return $this->render('index', [
            'block' => $block,
            'model' => $model,
            'user_lists' => $user_lists,
            'news_lists' => $news_lists,
            'type_requests' => $type_requests,
            'servise_category' => $servise_category,
            'servise_name' => $servise_name,
            'flat' => $flat,
            'status_list' => $status_list,
        ]);
        
    }
    
    /*
     * Метод просмотра списка заявок для выбранного пользователя
     */
    public function actionShowUserRequests() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $user_id = Yii::$app->request->post('userID');
        $type = Yii::$app->request->post('type');
        
        if (Yii::$app->request->isAjax) {
            switch ($type) {
                case 'requests':
                    $request_list = UserRequests::getRequestsByUserID($user_id);
                    break;
                case 'paid-requests':
                    $request_list = UserRequests::getPaidRequestsByUserID($user_id);
                    break;
                default:
                    return ['success' => false];
            }
            
            $data = $this->renderPartial("request-block/{$type}_list", ['user_lists' => $request_list]);
            return [
                'success' => true,
                'data' => $data,
            ];
        }
    }
    
    /*
     * Метод сохранения созданной заявки
     */
    public function actionCreateRequest() {
        
        $model = new RequestForm();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $number = $model->save();
            return $this->redirect(['requests/view-request', 'request_number' => $number]);
        }
    }
    
    /*
     * Метод сохранения созданной заявки на платную услугу
     */
    public function actionCreatePaidRequest() {
        
        $model = new PaidRequestForm();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $number = $model->save();
            return $this->redirect(['requests/view-paid-request', 'request_number' => $number]);
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
                ->select(['account_id', 'houses_gis_adress', 'houses_number', 'flats_number', 'personal_flat_id'])
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
    
}
