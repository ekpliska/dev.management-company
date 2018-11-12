<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\data\Pagination;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\News;
    use app\models\Rubrics;
    use app\models\Image;

/**
 * Новости
 */
class NewsController extends AppClientsController {
    
    /*
     * Новости, главная страница
     */
    public function actionIndex($rubric = 'important_information') {
        
        $estate_id = Yii::$app->userProfile->_user['estate_id'];
        $house_id = Yii::$app->userProfile->_user['house_id'];
        $flat_id = Yii::$app->userProfile->_user['flat_id'];
        $rubruc_id = $rubric;
        
        
        $query = News::getNewsByClients($rubruc_id, $estate_id, $house_id, $flat_id);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 6,
            'forcePageParam' => false,
            'pageSizeParam' => false,
        
        ]);
        
        $news = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();        
        
        $rubrucs = Rubrics::getArrayRubrics();
        
        return $this->render('index', [
            'news' => $news,
            'rubrucs' => $rubrucs,
            'pages' => $pages,
        ]);        
        
    }
    
    /*
     * Страница просмотра отдельной новости
     */
    public function actionViewNews($slug) {
        
        $news = News::findNewsBySlug($slug);
        if ($news === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        // Получаем список прикрепленных документоы к новости
        $files = Image::getAllDocByNews($news['news_id'], 'News');
        
        return $this->render('view-news', [
            'news' => $news,
            'files' => $files,
        ]);
    }    
    
}
