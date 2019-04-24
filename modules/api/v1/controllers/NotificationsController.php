<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use yii\rest\Controller;
    use app\modules\api\v1\models\notification\NotificationLists;
    

/**
 * Уведомления для текущего пользователя
 */
class NotificationsController extends Controller {
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['index', 'view'];
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
            'view' => ['get'],
        ];
    }
    
    /*
     * Формируем для текущего пользователя список уведомлений
     */
    public function actionIndex() {
        return NotificationLists::getNotifiactions();
    }
    
    /*
     * Удаляепм просмотренное уведомление
     */
    public function actionView($note_id) {
        $note = NotificationLists::findOne(['id' => $note_id]);
        if ($note) {
            return $note->delete(false) ? true : false;
        }
        return false;
    }
    
}
