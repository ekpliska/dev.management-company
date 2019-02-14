<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\modules\dispatchers\models\UserRequests;
    use app\modules\dispatchers\models\News;
    use app\modules\dispatchers\models\form\RequestForm;
    use app\models\TypeRequests;
    use app\models\PersonalAccount;

/**
 * Диспетчеры
 */
class DispatchersController extends AppDispatchersController {
    
    /*
     * Диспетчеры, главная страница
     */
    public function actionIndex($block = 'requests') {
        
        $type_requests = TypeRequests::getTypeNameArray();
        $flat = [];
        
        switch ($block) {
            case 'requests':
                $model = new RequestForm();
                $user_lists = UserRequests::getRequestsByUser();
                $news_lists = News::getNewsList($count_news = 7);
                break;
            case 'paid-requests':
                $model = [];
                $user_lists = [];
                $requests_lists = [];
                break;
        }
        
        return $this->render('index', [
            'block' => $block,
            'model' => $model,
            'user_lists' => $user_lists,
            'news_lists' => $news_lists,
            'type_requests' => $type_requests,
            'flat' => $flat,
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
                case 'paid-request':
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
    
}
