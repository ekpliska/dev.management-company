<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use app\modules\managers\models\News;
    use app\modules\managers\models\Requests;
    use app\modules\managers\models\PaidServices;
    use app\models\Voting;

/**
 * Профиль Админимтратора
 *
 */
class ManagersController extends AppManagersController {
    
    /*
     * Назначение прав доступа к модулю "Администратор"
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['administrator'],
                    ],
                ],
            ],
        ];
    }
    
    /*
     * Обработка ошибок
     */
    public function actionError() {
        
        $exception = Yii::$app->errorHandler->exception;
        $exception->statusCode == '404' ? $this->view->title = "Ошибка 404" : '';
        $exception->statusCode == '403' ? $this->view->title = "Доступ запрещён" : '';
        $exception->statusCode == '500' ? $this->view->title = "Внутренняя ошибка сервера" : '';
        
        return $this->render('error', ['message' => $exception->getMessage()]);
    }
    
    /*
     * Главная страница
     */
    public function actionIndex() {
        
        // Формируем список последних новостей и голосования, 10
        $news_content = News::getAllNewsAndVoting();
        
        // Формируем список последних 10 новых заявок
        $request_list = Requests::getOnlyNewRequest();
        
        // Формируем список актиынх опросов
        $active_vote = Voting::getActiveVote();
        
        // Формируем список последних 10 новых заявок на платные услуги
        $paid_request_list = PaidServices::getOnlyNewPaidRequest();
        
        return $this->render('index', [
            'news_content' => $news_content,
            'request_list' => $request_list,
            'paid_request_list' => $paid_request_list,
            'active_vote' => $active_vote,
        ]);
        
    }
    
}