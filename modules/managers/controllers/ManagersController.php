<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use app\modules\managers\models\News;
    use app\modules\managers\models\Requests;
    use app\modules\managers\models\PaidServices;
    use app\models\Voting;
    use app\modules\managers\models\User;
    use app\models\Organizations;
    use app\models\Services;
    use app\models\NotesFlat;

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
        
        
        // Формируем список последних 10 новых заявок
        $request_list = Requests::getOnlyNewRequest();
        // Формируем список новых заявок на платные услуги
        $paid_request_list = PaidServices::getOnlyNewPaidRequest();
        
        // Формируем список актиынх опросов
        $active_vote = Voting::getActiveVote();
        // Формируем список последних новостей
        $news_content = News::getAllNewsAndVoting();
        
        // Формируем список последних зарегистрированных пользователей
        $user_lists = User::getNewUser();
        
        // Получить информацию об управляющей организации
        $organization_info = Organizations::find()->where(['organizations_id' => 1])->one();
        
        // Новые платные услуги
        $new_services = Services::getLastNewSevices();
        
        // Задолжности по квартирам
        $notice_debt = NotesFlat::getLastNotice();
        
        return $this->render('index', [
            'news_content' => $news_content,
            'request_list' => $request_list,
            'paid_request_list' => $paid_request_list,
            'active_vote' => $active_vote,
            'user_lists' => $user_lists,
            'organization_info' => $organization_info,
            'new_services' => $new_services,
            'notice_debt' => $notice_debt,
        ]);
        
    }
    
}