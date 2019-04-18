<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\rest\Controller;
    use app\models\User;
    use app\modules\api\v1\models\chat\Chat;

/**
 * Чат
 */
class ChatController extends Controller {
        
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['index', 'get-messages'];
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
    
    public function getUser() {
        
        return User::find()
                ->with(['client'])
                ->where(['user_id' => Yii::$app->user->getId()])
                ->one();
    }
    
}
