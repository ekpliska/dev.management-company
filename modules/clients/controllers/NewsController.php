<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\data\Pagination;
    use yii\web\NotFoundHttpException;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\News;
    use app\models\Image;
    use app\models\Rubrics;

/**
 * Новости
 */
class NewsController extends AppClientsController {
    
    public function actionIndex($block = 'important_information') {
        
        // Получаем ID текущего лицевого счета
        $account_id = $this->_current_account_id;
        // Получаем массив содержащий ID ЖК, ID дома, ID квартиры, номер подъезда
        $living_space = Yii::$app->userProfile->getLivingSpace($account_id);
        
        switch ($block) {
            case 'important_information':
            case null: {
                $news = News::getNewsByClients($living_space, Rubrics::RUBRIC_INFORMATION);
                break;
            }
            case 'special_offers': {
                $news = News::getNewsByClients($living_space, Rubrics::RUBRIC_ADVERTS);
                break;
            }
            case 'house_news': {
                $news = News::getNewsByClients($living_space, Rubrics::RUBRIC_HOUSE);
                break;
            }
        }
        
        $pages = new Pagination([
            'totalCount' => $news->count(), 
            'pageSize' => 9, 
            'forcePageParam' => false, 
            'pageSizeParam' => false,
        ]);
        
        $news = $news->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        
        return $this->render('index', [
            'news' => $news,
            'pages' => $pages,
        ]);
        
    }
    
    /*
     * Страница просмотра отдельной новости
     */
    public function actionView($slug) {
        
        $news = News::findNewsBySlug($slug);
        if ($news === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        // Получаем список прикрепленных документов к новости
        $files = Image::getAllDocByNews($news['news_id'], 'News');
        
        return $this->render('view', [
            'news' => $news,
            'files' => $files,
        ]);
    }    
    
}
