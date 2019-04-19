<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\rest\Controller;
    use app\models\User;
    use app\modules\api\v1\models\chat\Chat;
    use app\modules\api\v1\models\chat\MessageForm;

/**
 * Чат
 */
class ChatController extends Controller {
        
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['index', 'get-messages', 'send-message'];
        $behaviors['authenticator']['authMethods'] = [
              HttpBasicAuth::className(),
              HttpBearerAuth::className(),
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
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
            'index' => ['get'],
            'get-messages' => ['get'],
            'send-message' => ['post'],
        ];
    }
    
    /*
     * Получить список всех чатов из Заявок, Опросов
     */
    public function actionIndex() {
        
        $user = $this->getUser();
        $chat = new Chat($user);
        return $chat->getChatList();
        
    }
    
    /*
     * Сообщения чата
     */
    public function actionGetMessages($type, $chat_id) {
        
        $user = $this->getUser();
        $chat = new Chat($user);
        return $chat->getChatMessages($type, $chat_id);
        
    }
    
    /*
     * Отправка сообщения
     * {
     *      "type_chat": "vote",
     *      "chat_id": "1",
     *      "message": "text message"
     * }
     */
    public function actionSendMessage() {
        
        $model = new MessageForm();
        $model->load(Yii::$app->request->getBodyParams(), '');
        if ($model->send()) {
            return ['success' => true];
        }
        return ['message' => 'Ошибка отправки сообщения'];
        
    }
    
    public function getUser() {
        
        return User::find()
                ->with(['client'])
                ->where(['user_id' => Yii::$app->user->getId()])
                ->one();
    }
    
}
