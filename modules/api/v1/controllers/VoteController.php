<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use app\modules\api\v1\models\vote\VoteList;

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
            'only' => ['index'],
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
        ];
    }
    
    public function actionIndex($account) {
        
        $voting_list = VoteList::getFullVoteList($account);
        
        if (!$voting_list) {
            return ['success' => false];
        }
        
        return $voting_list;
        
    }
}