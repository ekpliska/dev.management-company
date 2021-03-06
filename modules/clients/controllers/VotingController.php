<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\Voting;
    use app\models\RegistrationInVoting;
    use app\modules\clients\models\form\CheckSMSVotingForm;
    use app\models\Answers;
    use app\modules\clients\models\UserProfile;
    use app\modules\clients\models\form\SendMessageForm;

/**
 * Голосование
 */
class VotingController extends AppClientsController {
    
    /*
     * Голосование, главная страница
     */
    public function actionIndex() {
        
        if (Yii::$app->user->can('clients_rent')) {
            throw new \yii\web\NotFoundHttpException();
        }
        
        $house_id = Yii::$app->userProfile->_user['house_id'];
        
        $voting_list = Voting::findAllVotingForClient($house_id);
        
        return $this->render('index', [
            'voting_list' => $voting_list,
        ]);
        
    }
    
    /*
     * Страница конечного голосования
     */
    public function actionView($voting_id) {
        
        if (Yii::$app->user->can('clients_rent')) {
            throw new \yii\web\NotFoundHttpException();
        }
        
        /*
         *  Проверем наличие куки на загрузку модального окна ввода СМС кода, 
         *  если кука существует, то загружаем модальное окно сразу при загрузке страницы с голосованием
         */
        $modal_show = $this->getCookieVoting($voting_id) ? true : false;
        
        // Получаем информацию по текущему голосованию
        $voting = Voting::findVotingById($voting_id);
        if ($voting == null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        // Получаем информаию о участниках голосования
        $participants = RegistrationInVoting::getParticipants($voting_id);
        
        // Проверяем наличие текущего пользователя в списке зарегистрированных участников голосования
        $is_register = RegistrationInVoting::findById($voting_id, $voting['status']);
        
        // Загружаем модель на ввод СМС кода
        $model = new CheckSMSVotingForm($is_register['random_number']);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->checkSmsCode($voting_id)) {
                // Если все ОК, удаляем куку модального окна о регистрации
                $this->deleteCookieVoting($voting_id);
                Yii::$app->session->setFlash('success', ['message' => "Вы были зарегистрированы на участие в голосовании {$voting['voting_title']}"]);
                return $this->refresh();
            }
            Yii::$app->session->setFlash('error', ['message' => 'При регистрации в голосовании возникла ошибка. Обновите страницу и повторите действие заново']);
            return $this->refresh();
        }
        
        /*
         * Отправка сообщения в чате
         */
        if (Yii::$app->request->isPjax) {
            $model = new SendMessageForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->send($voting_id);
            }
        }
        
        return $this->render('view', [
            'voting' => $voting,
            'is_register' => $is_register ? $is_register : null,
            'model' => $model,
            'modal_show' => $modal_show,
            'participants' => $participants,
        ]);
        
    }
    
    /*
     * Регистрация на участие в голосовании
     * 
     * При нажатии на кнопку "Принять участние" создаем запись в таблице "Участники"
     * Геристрируем случайное число, которое будем отправлять в СМС пользователлю
     * Формируем время ожидания ввода сгенерированного кода
     * Если время истекает, запись из бд о регистрации на участие в голосованиии удаляется
     * 
     * Если регистрация на участие прошла успешно, записываем куку модального окна ввода СМС кода
     */
    public function actionParticipateInVoting() {
        
        if (Yii::$app->user->can('clients_rent')) {
            throw new \yii\web\NotFoundHttpException();
        }
        
        $voting_id = Yii::$app->request->post('voting');
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $register = new RegistrationInVoting();
            if ($register->registerIn($voting_id)) {
                $this->setCookieVoting($voting_id);
                return ['success' => true, 'voting_id' => $voting_id];
            }
            return ['success' => false];
        }
        return ['success' => false];
        
    }
        
    /*
     * Отказ от участия в голосовании
     */
    public function actionRenouncementToParticipate($voting_id) {
        
        if (Yii::$app->user->can('clients_rent')) {
            throw new \yii\web\NotFoundHttpException();
        }
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (RegistrationInVoting::deleteRegisterById($voting_id)) {
                $this->deleteCookieVoting($voting_id);
            }
            return ['success' => true];
        }
        return ['success' => false];
        
    }
    
    /*
     * Повторная отправка вновь сгенерированного кода
     */
    public function actionRepeatSmsCode() {
        
        if (Yii::$app->user->can('clients_rent')) {
            throw new \yii\web\NotFoundHttpException();
        }
        
        $voting_id = Yii::$app->request->post('votingId');
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $register = RegistrationInVoting::findById($voting_id);
            if (!$register->generateNewSMSCode()) {
                return ['success' => false];
            }
            return ['success' => true];
        }
        return ['success' => false];
                
    }
    
    /*
     * Метод записи куки
     */
    public function setCookieVoting($voting_id) {
        
        $cookies = Yii::$app->response->cookies;
        $name_modal = '_participateInVoting-' . $voting_id;
      
        // Количество минут для хранения куки
        $minutes_to_add = 10;

        $cookies->add(new \yii\web\Cookie([
            'name' => $name_modal,
            'value' => $voting_id,
            'expire' => strtotime("+ {$minutes_to_add} minutes"),
        ]));
      
    }
    
    /*
     * Получение куки 
     * 
     * Если заданной куки не существует, удаляем запись регистрации участия в голосовании
     */
    private function getCookieVoting($voting_id) {
        
        $cookies = Yii::$app->request->cookies;
        $name_modal = '_participateInVoting-' . $voting_id;
        
        if (!$cookies->has($name_modal)) {
            $register = RegistrationInVoting::deleteRegisterById($voting_id);
            return false;
        } 
        return true;
    }
    
    /*
     * Удаление куки
     * В случае отказа от участия в голосовании удаляем куку
     */
    private function deleteCookieVoting($voting_id) {
        $cookies = Yii::$app->response->cookies;
        $name_modal = '_participateInVoting-' . $voting_id;
        return $cookies->remove($name_modal) ? true : false;
        
    }
    
    /*
     * Отправка ответа голосования
     */
    public function actionSendAnswer($question_id, $type_answer) {
        
        if (Yii::$app->user->can('clients_rent')) {
            throw new \yii\web\NotFoundHttpException();
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isPost) {
            if (Answers::sendAnswer($question_id, $type_answer)) {
                return ['success' => true];
            }
            return ['success' => false];
        }
        return ['success' => false];
    }
    
    /*
     * Завершение голосования
     */
    public function actionFinishVoting($voting_id) {
        
        if (Yii::$app->user->can('clients_rent')) {
            throw new \yii\web\NotFoundHttpException();
        }
        
        if (!is_numeric($voting_id)) {
            Yii::$app->session->setFlash('error', ['message' => 'Возникла ошибка. Обновите страницу и повторите действие заново']);
            return $this->redirect(['view', 'voting_id' => $voting_id]);
        }
        
        if (Yii::$app->request->isPost) {
            if (RegistrationInVoting::finishVoting($voting_id)) {
                Yii::$app->session->setFlash('success', ['message' => 'Спасибо, ваш голос учтен. Участие в голосовании завершено']);
                return $this->redirect(['view', 'voting_id' => $voting_id]);
            }
            Yii::$app->session->setFlash('error', ['message' => 'Возникла ошибка. Обновите страницу и повторите действие заново']);
            return $this->redirect(['view', 'voting_id' => $voting_id]);
        }
    }
    
    /*
     * Просмотр профиля проголосовавших участников
     */
    public function actionViewProfile($user_id) {
        
        if (Yii::$app->user->can('clients_rent')) {
            throw new \yii\web\NotFoundHttpException();
        }
        
        $user_info = UserProfile::userInfo($user_id);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('modal/view-profile', [
                'user_info' => $user_info]);
        }
    }
    
    /*
     * Отправка сообщения в чате
     */
    public function actionSendMessage($vote) {
        
        if (Yii::$app->user->can('clients_rent')) {
            throw new \yii\web\NotFoundHttpException();
        }
        
        if (Yii::$app->request->isPjax) {
            $model = new SendMessageForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->send($vote);
                $response = Yii::$app->getResponse();
                $response->format = \yii\web\Response::FORMAT_JSON;
                $response->send();
            }
        }
    }
    
}
