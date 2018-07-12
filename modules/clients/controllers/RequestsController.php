<?php

    namespace app\modules\clients\controllers;
    use yii\web\Controller;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\data\ActiveDataProvider;
    use app\models\Requests;
    use app\modules\clients\models\AddRequest;
    use app\models\User;
    use app\models\Houses;
    use app\modules\clients\models\FilterForm;

/**
 * Заявки
 */
class RequestsController extends Controller
{
    /**
     * Главная страница
     */
    public function actionIndex($user, $username, $account)
    {
        // Делаем проверку, правильности переданных параметров в запросе url
        $user_info = User::findByUser($user, $username, $account);
        
        // Если запрос пустой, кидаем исключение
        if ($user_info === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
       
        // Модель для фильтра по типу заявок
        $model_filter = new FilterForm();
        
        // В датапровайдер собираем все заявки по текущему пользователю
        $all_requests = new ActiveDataProvider([
            'query' => Requests::findByUser($user),
        ]);

        /*
         * Определяем модель добавления новой заявки
         * Если данные получены и провалидированы сохраняем заявку
         */
        // $model = new AddRequest();
        $model = new Requests([
                'scenario' => Requests::SCENARIO_ADD_REQUEST,
            ]);
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->addRequest($user)) {
                    $model->gallery = \yii\web\UploadedFile::getInstances($model, 'gallery');
                    $model->uploadGallery();
                
                Yii::$app->session->setFlash('success', 'Заявка создана');                
            } else {
                Yii::$app->session->setFlash('error', 'Возникла ошибка');
            }
            return $this->refresh();
        }

        return $this->render('index', [
            'all_requests' => $all_requests,
            'type_requests' => \app\models\TypeRequests::getTypeNameArray(),
            'model' => $model,
            'model_filter' => $model_filter,
        ]);
    }
    
    /*
     * Страница отдельной заявки
     */    
    public function actionViewRequest($request_numder) {
        
        // Ищем заявку по уникальному номеру
        $request_info = Requests::findRequestByIdent($request_numder);
        
        // Если заявка не найдена, кидаем исключение
        if ($request_info === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $account_id = Yii::$app->user->identity->user_account_id;        
        $user_house = Houses::findByAccountId($account_id);
        
        return $this->render('view-request', ['request_info' => $request_info, 'user_house' => $user_house]);        
    }
    
    
    /*
     * Фильтр заявок по типу заявки
     */
    public function actionFilterByTypeRequest() {
        
        $rent_id = Yii::$app->request->post('rent_id');
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            $model_filter = new FilterForm();
            $all_requests = $model_filter->searchRequest($rent_id);
            return $this->renderAjax('grid', ['all_requests' => $all_requests]);
        }
    }
            
 }
