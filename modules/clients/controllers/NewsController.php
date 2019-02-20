<?php

    namespace app\modules\clients\controllers;
    use yii\web\NotFoundHttpException;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\News;
    use app\models\Image;

/**
 * Новости
 */
class NewsController extends AppClientsController {
    
    /*
     * Страница просмотра отдельной новости
     */
    public function actionViewNews($slug) {
        
        $news = News::findNewsBySlug($slug);
        if ($news === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        // Получаем список прикрепленных документов к новости
        $files = Image::getAllDocByNews($news['news_id'], 'News');
        
        return $this->render('view-news', [
            'news' => $news,
            'files' => $files,
        ]);
    }    
    
}
