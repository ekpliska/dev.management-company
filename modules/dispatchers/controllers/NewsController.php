<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use yii\data\Pagination;
    use yii\web\UploadedFile;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\models\Houses;
    use app\modules\dispatchers\models\searchForm\searchNews;
    use app\models\News;
    use app\models\Rubrics;
    use app\models\Partners;
    use app\modules\dispatchers\models\form\NewsForm;

/**
 * Новости
 */
class NewsController extends AppDispatchersController {
    
    /*
     * Новости, главная страница
     */
    public function actionIndex() {
        
        $house_lists = Houses::getHousesList(false);
        $type_publication = Houses::getTypePublication();
        
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
            'type_publication' => $type_publication,
            'model' => $model,
            'all_news' => $posts,
            'pages' => $pages,
        ]);
        
    }

    /*
     * Создание публикации
     * 
     * $status_publish array Статус размещения публикации
     * $rubrics array Тип публикации
     */
    public function actionCreate() {
        
        $model = new NewsForm();
        // Тип публикации (Для всех, Для конкретного дома)
        $status_publish = News::getStatusPublish();
        // Тип уведомления
        $notice = News::getNoticeType();
        // Тип рубрики
        $rubrics = Rubrics::getArrayRubrics();
        $houses = [];
        // Партнеры, если публикация - рекламная запись
        $parnters = Partners::getAllParnters();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Превью
            $file = UploadedFile::getInstance($model, 'preview');
            $model->preview = $file;
            // Прикрепленные файлы
            $files = UploadedFile::getInstances($model, 'files');
            $model->files = $files;
            
            $slug = $model->save($file, $files);            
            if ($slug) {
                Yii::$app->session->setFlash('news-admin', [
                    'success' => true,
                    'message' => 'Новость была успешно добавлена',
                ]);                
                return $this->redirect(['view', 'slug' => $slug]);
            } else {
                Yii::$app->session->setFlash('news-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'status_publish' => $status_publish,
            'notice' => $notice,
            'rubrics' => $rubrics,
            'houses' => $houses,
            'parnters' => $parnters,
        ]);
    }
    
}
