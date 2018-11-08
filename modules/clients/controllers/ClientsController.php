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
 * Default controller for the `clients` module
 */
class ClientsController extends AppClientsController
{
    
    /**
     * Главная страница
     * Формирование списка новостей для собственника
     */
    public function actionIndex($rubric = 'important_information') {

//        var_dump($this->_value_choosing); die();
        
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
    
    public function actionVote() {
        
        if (!Yii::$app->user->can('clients')) {
            throw new NotFoundHttpException('Пользователю с учетной записью Арендатор, доступ к данной странице запрещен');
        }
        
        return $this->render('vote');
        
    }
    
}
