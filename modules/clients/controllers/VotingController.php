<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\Voting;
    use app\models\RegistrationInVoting;

/**
 * Голосование
 */
class VotingController extends AppClientsController {
    
    /*
     * Голосование, главная страница
     */
    public function actionIndex() {
        
        $estate_id = Yii::$app->userProfile->_user['estate_id'];
        $house_id = Yii::$app->userProfile->_user['house_id'];
        $flat_id = Yii::$app->userProfile->_user['flat_id'];        
        
        $voting_list = Voting::findAllVotingForClient($estate_id, $house_id, $flat_id);
        
        return $this->render('index', [
            'voting_list' => $voting_list,
        ]);
        
    }
    
    /*
     * Страница конечного голосования
     */
    public function actionViewVoting($voting_id) {
        
        $voting = Voting::findVotingById($voting_id);
        
        return $this->render('view-voting', [
            'voting' => $voting,
        ]);
        
    }
    
    /*
     * Регистрация на участие в голосовании
     * 
     * При нажатии на кнопку "Принять участние" создаем запись в таблице "Участники"
     * Генерируем случайное число, которое будем отправлять в СМС пользователлю
     * Формируем время ожидания ввода сгенерированного числа
     * Если время истекает, запись из бд о регистрации на участие в голосованиии удаляется
     */
    public function actionParticipateInVoting() {
        
        $voting_id = Yii::$app->request->post('voting');
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $register = new RegistrationInVoting();
            if ($register->registerIn($voting_id)) {
                return ['success' => true, 'voting_id' => $voting_id];
            }
            return ['success' => false];
        }
        return ['success' => false];
        
    }
    
}
