<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use app\modules\api\v1\models\vote\VoteList;
    use app\modules\api\v1\models\vote\QuestionList;
    use app\modules\api\v1\models\vote\ResultsVote;
    use app\models\SmsSettings;

/**
 * Опросы
 */
class VoteController extends Controller {
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];
        
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'send-sms', 'get-questions', 'set-answers', 'get-results', 'become-participant'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
        
    }
    
    public function verbs() {
        
        return [
            'vote-list' => ['get'],
            'send-sms' => ['get'],
            'get-questions' => ['get'],
            'set-answers' => ['post'],
            'get-results' => ['get'],
            'become-participant' => ['get']
        ];
    }
    
    /*
     * Получить список всех опросов, для текущего пользователя
     */
    public function actionVoteList($account) {
        
        $voting_list = VoteList::getFullVoteList($account);
        if (!$voting_list) {
            return ['success' => false];
        }
        return $voting_list;
        
    }
    
    /*
     * Отправка СМС-кода на регистрацию в голосовании
     */
    public function actionSendSms() {
        
        $user_phone = Yii::$app->user->identity->user_mobile;
        
        // Генерируем случайное число, СМС код
        $sms_code = rand(10000, 99999);
        $phone = preg_replace('/[^0-9]/', '', $user_phone);
        
        // Отправляем смс на указанный номер телефона
        if (!$result = Yii::$app->sms->generalMethodSendSms(SmsSettings::TYPE_NOTICE_PARTICIPANT_VOTING, $phone, $sms_code)) {
            return ['success' => false, 'message' => $result];
        }
        
        return [
            'success' => true,
            'sms_code' => (string)$sms_code,
        ];
        
    }
    
    /*
     * Получить список вопросов по гоолосованию
     */
    public function actionGetQuestions($vote_id) {
        
        if (!VoteList::isParticipant($vote_id)) {
            return [
                'success' => false,
                'message' => 'Вы не являетесь участником данного опроса',
            ];
        }
        
        $questions_list = QuestionList::getQuestions($vote_id);
        return $questions_list ? $questions_list : ['success' => false];
        
    }
    
    /*
     * Получить список ответов
     * {
     *      "vote_id": "1",
     *      "answers": {
     *          "question_id": "answer",    ID Вопроса: Ответ
     *          "question_id": "answer",
     *          "question_id": "answer"
     *      }
     * }
     * answer behind "За"
     * answer against "Против"
     * answer abstain "Воздержаться"
     */
    public function actionSetAnswers($vote_id) {
        
        if (!VoteList::isParticipant($vote_id)) {
            return [
                'success' => false,
                'message' => 'Вы не являетесь участником данного опроса',
            ];
        }
        
        // Собираем данные и пост запроса
        $data_post = Yii::$app->request->getBodyParams();
        if (!key_exists('vote_id', $data_post) && !key_exists('answers', $data_post)) {
            return [
                'success' => false,
            ];
        }
        
        // Отпрвляем ответы на вопросы
        if (!VoteList::sendAnswer($data_post)) {
            return ['success' => false];
        }
        return ['success' => true];
        
    }
    
    /*
     * Результаты опроса
     */
    public function actionGetResults($vote_id) {
        
        if (!VoteList::isParticipant($vote_id)) {
            return [
                'success' => false,
                'message' => 'Вы не являетесь участником данного опроса',
            ];
        }
        
        $results_vote = ResultsVote::getResults($vote_id);
        
        return $results_vote ? $results_vote : ['success' => false];
    }
    
    /*
     * Регистрация после успешного ввода СМС
     */
    public function actionBecomeParticipant($vote_id) {
        
        if (!VoteList::registerToVote($vote_id)) {
            return ['success' => false];
        }
        return ['success' => true];
    }
    
}