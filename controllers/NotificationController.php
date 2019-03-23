<?php

    namespace app\controllers;
    use Yii;
    use yii\web\Controller;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use app\models\Notifications;

/**
 * Управление Уведомлениями
 */
class NotificationController extends Controller {
    
    public function behaviors() {
        return [            
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['remove-notifications', 'one-notification'],
                'rules' => [
                    [
                        'actions' => ['remove-notifications', 'one-notification'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'remove-notifications' => ['post'],
                    'one-notification' => ['post'],
                ],
            ],
        ];
    }
    
    /*
     * Удалить все уведомления текущего пользователя
     */
    public function actionRemoveNotifications() {
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            if (Notifications::deleteAll(['user_uid' => Yii::$app->user->id])) {
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
        
    }
    
    /*
     * Удалить уведомление, по которому был совершен клик
     */
    public function actionOneNotification($notice_id) {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            if (Notifications::findOneNotice($notice_id)) {
                return ['success' => true];
            }
        }
        ['success' => false];
        
    }
    
}
