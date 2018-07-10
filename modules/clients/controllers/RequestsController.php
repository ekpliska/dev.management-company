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
        $user_info = User::findByUser($user, $username, $account);
        
        if ($user_info === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $model = new AddRequest();
        
        $all_requests = new ActiveDataProvider([
            'query' => Requests::findByUser($user),
        ]);
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->addRequest($user)) {
                Yii::$app->session->setFlash('success', 'Заявка создана');
            } else {
                Yii::$app->session->setFlash('error', 'Возникла ошибка');
            }
            return $this->refresh();
        }        
        
        return $this->render('index', ['all_requests' => $all_requests, 'model' => $model]);
    }
    
    public function actionViewRequest($request_numder) {
        $request_info = Requests::findRequestByIdent($request_numder);
        $account_id = Yii::$app->user->identity->user_account_id;
        
        $user_house = Houses::findByAccountId($account_id);
        // var_dump($user_house->getAdress());die;
        
        if ($request_info === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        return $this->render('view-request', ['request_info' => $request_info, 'user_house' => $user_house]);        
    }
    
 }
