<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use yii\data\Pagination;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\models\Houses;
    use app\modules\dispatchers\models\searchForm\searchNews;

/**
 * Новости
 */
class NewsController extends AppDispatchersController {
    
    /*
     * Новости, главная страница
     */
    public function actionIndex() {
        
        $house_lists = Houses::getHousesList(false);
        
        $model = new searchNews();
        $results = $model->search(Yii::$app->request->queryParams);
        
        $pages = new Pagination([
            'totalCount' => $results->count(), 
            'pageSize' => 9, 
            'forcePageParam' => false, 
            'pageSizeParam' => false,
        ]);
        
        $posts = $results->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        
        return $this->render('index', [
            'house_lists' => $house_lists,
            'model' => $model,
            'all_news' => $posts,
            'pages' => $pages,
        ]);
        
    }

    
}
