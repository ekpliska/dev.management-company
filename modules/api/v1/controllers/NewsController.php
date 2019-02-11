<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use yii\filters\AccessControl;
    use yii\filters\auth\HttpBasicAuth;
    use yii\filters\auth\HttpBearerAuth;
    use app\modules\api\v1\models\UserProfile;
    use app\modules\api\v1\models\News;
    

/**
 * Получение новостей
 */
class NewsController extends Controller
{
    
    public function behaviors() {
        
        $behaviors = parent::behaviors();
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
    
    public function verbs() {
        
        return [
            'important-information' => ['get'],
            'adverts-information' => ['get'], 
            'housing-information' => ['get'],
            'view' => ['get'],
        ];
    }    

    
    /**
     * Все новости с рубрикой "Важная информация"
     */
    public function actionImportantInformation() {
        
        $user_info = UserProfile::userProfile(Yii::$app->user->id);
        $house_id = array_shift($user_info['personal_account'])['living_area']['house_id'];
        $news_lists = News::importantInformation($house_id);
        
        if (empty($news_lists)) {
            return ['success' => false];
        }
        
        return [
            'success' => true,
            'news_lists' => $news_lists,
        ];
    }
    
    /*
     * Рекламные публикации
     */
    public function actionAdvertsInformation() {
        
        $user_info = UserProfile::userProfile(Yii::$app->user->id);
        $house_id = array_shift($user_info['personal_account'])['living_area']['house_id'];
        $news_lists = $results = News::otherNews($house_id, true);
        
        if (empty($news_lists)) {
            return ['success' => false];
        }
        
        return [
            'success' => true,
            'news_lists' => $news_lists,
        ];
    }
    
    /*
     * Публикации "Новости дома"
     */
    public function actionHousingInformation() {
        
        $user_info = UserProfile::userProfile(Yii::$app->user->id);
        $house_id = array_shift($user_info['personal_account'])['living_area']['house_id'];
        $news_lists = $results = News::otherNews($house_id, false);
        
        if (empty($news_lists)) {
            return ['success' => false];
        }
        
        return [
            'success' => true,
            'news_lists' => $news_lists,
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
