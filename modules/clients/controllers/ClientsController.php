<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use app\modules\clients\controllers\AppClientsController;
    

/**
 * Default controller for the `clients` module
 */
class ClientsController extends AppClientsController
{
    
    /**
     * Главная страница
     */
    public function actionIndex() {
        return $this->render('index');
    }
    
    public function actionVote() {
        
        if (!Yii::$app->user->can('clients')) {
            throw new NotFoundHttpException('Пользователю с учетной записью Арендатор, доступ к данной странице запрещен');
        }
        
        return $this->render('vote');
        
    }
    
}
