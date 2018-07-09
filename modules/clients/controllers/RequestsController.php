<?php

    namespace app\modules\clients\controllers;
    use yii\web\Controller;
    use Yii;
    use yii\data\ActiveDataProvider;
    use app\models\Requests;
    use app\modules\clients\models\AddRequest;

/**
 * Заявки
 */
class RequestsController extends Controller
{
    /**
     * Главная страница
     */
    public function actionIndex($username)
    {
        $model = new AddRequest();
        
        if ($username === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $all_requests = new ActiveDataProvider([
            'query' => Requests::findByUser($username),
        ]);
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->addRequest($username)) {
                Yii::$app->session->setFlash('success', 'Заявка создана');
            } else {
                Yii::$app->session->setFlash('error', 'Возникла ошибка');
            }
            return $this->refresh();
        }        
        
        return $this->render('index', ['all_requests' => $all_requests, 'model' => $model]);
    }
    
 }
