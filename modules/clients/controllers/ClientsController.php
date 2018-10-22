<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
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
    public function actionIndex($rubric = null) {

        $estate_id = Yii::$app->userProfile->_user['estate_id'];
        $house_id = Yii::$app->userProfile->_user['house_id'];
        $flat_id = Yii::$app->userProfile->_user['flat_id'];
        $rubruc_id = $rubric ? $rubric : 1;
        
        $news = News::getNewsByClients($rubruc_id, $estate_id, $house_id, $flat_id);
        $rubrucs = Rubrics::getArrayRubrics();
        
        return $this->render('index', [
            'news' => $news,
            'rubrucs' => $rubrucs,
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
