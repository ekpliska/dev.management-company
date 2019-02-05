<?php

    namespace app\modules\api\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use app\modules\api\models\UserProfile;
    use app\modules\api\models\News;
    

/**
 * Получение новостей
 */
class NewsController extends Controller
{
    
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];
        
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'view'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
        
    }

    
    /**
     * Все новости по рубрикам
     */
    public function actionIndex($block = 'important_information') {
        
        $user_info = UserProfile::userProfile(Yii::$app->user->id);
        
        switch ($block) {
            case 'important_information':
                $results = News::importantInformation($user_info['house_id']);
                break;
            case 'special_offers':
                $results = News::otherNews($user_info['house_id'], true);
                break;
            case 'house_news':
                $results = News::otherNews($user_info['house_id'], false);
                break;
            default:
                return ['success' => false];
        }
        
        return [
            'success' => true,
            'results' => $results,
        ];
        
        
    }
    
    /*
     * Отдельнкая новость
     */
    public function actionView($id) {
        
        $news = News::findNewsByID($id);
        
        if ($news == null) {
            return ['success' => false];
        }
        
        return $news;
        
    }
    
}
