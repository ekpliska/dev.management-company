<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\data\ActiveDataProvider;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\form\NewsForm;
    use app\modules\managers\models\News;
    use app\models\Rubrics;
    use app\models\Houses;
    use app\models\HousingEstates;
    use app\helpers\FormatHelpers;
    use app\modules\managers\models\HousesEstates;
    use app\models\Image;
    use app\models\Partners;

/**
 * Новости
 */
class NewsController extends AppManagersController {
    
    /*
     * Действия
     *      image-upload Загрузка изображений используемых в редакторе текста новостей
     *      file-delete Удаление изображений загруженных через редактор текста новостей
     */
    public function actions() {
        
        return [
            
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => 'http://dev.management-company/web/upload/news', // Directory URL address, where files are stored.
                'path' => '@webroot/upload/news', // Or absolute path to directory where files are stored.
            ],
            
            'file-delete' => [
                'class' => 'vova07\imperavi\actions\DeleteFileAction',
                'url' => 'http://dev.management-company/web/upload/news', // Directory URL address, where files are stored.
                'path' => '/var/www/my-site.com/dev.management-company/web/upload/news', // Or absolute path to directory where files are stored.
            ],            
         
       ]; 
        
    }
    
    /*
     * Новости, главная страница
     */
    public function actionIndex($section = 'news') {
        
        switch ($section) {
            case 'news':
                $results = News::getAllNews($adver = false);
//                $results = new ActiveDataProvider([
//                    'query' => News::getAllNews($adver = false),
//                    'pagination' => [
//                        'forcePageParam' => false,
//                        'pageSizeParam' => false,
//                        'pageSize' => 15,
//                    ],
//                ]);
                break;
            case 'adverts':
                $results = new ActiveDataProvider([
                    'query' => News::getAllNews($adver = true),
                    'pagination' => [
                        'forcePageParam' => false,
                        'pageSizeParam' => false,
                        'pageSize' => 15,
                    ],
                ]);
                break;
        }
        
        return $this->render('index', [
            'section' => $section,
            'results' => $results,
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
    
    /*
     * Просмотр, редактирование публикации
     */
    public function actionView($slug) {
        
        $news = News::findNewsBySlug($slug);
        
        if ($news == null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $status_publish = News::getStatusPublish();
        $notice = News::getNoticeType();
        $type_notice = News::getNoticeType();
        $rubrics = Rubrics::getArrayRubrics();
        $houses = empty($news->news_house_id) ? [] : Houses::getHousesList($for_list = true);
        $parnters = Partners::getAllParnters();
        
        // Получаем прикрепленные к заявке файлы
        $docs = Image::getAllDocByNews($news->news_id, $model_name = 'News');
        
        if ($news->load(Yii::$app->request->post())) {
            $is_valid = $news->validate();
            
            if ($is_valid) {
                // Превью
                $file = UploadedFile::getInstance($news, 'news_preview');
                $news->uploadImage($file);
                
                // Прикрепленные документы
                $files = UploadedFile::getInstances($news, 'files');
                $news->uploadFiles($files);
                
                Yii::$app->session->setFlash('success', ['message' => 'Изменения были успешно сохранены']);
                return $this->redirect(['view', 'slug' => $news->slug]);
                
            } else {
                Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз']);
                return $this->redirect(['view', 'slug' => $news->slug]);

            }
            
        }
        
        return $this->render('view', [
            'news' => $news,
            'status_publish' => $status_publish,
            'notice' => $notice,
            'type_notice' => $type_notice,
            'rubrics' => $rubrics,
            'houses' => $houses,
            'parnters' => $parnters,
            'docs' => $docs,
        ]);
    }
    
    /*
     * Зависимый переключатель статуса публикации
     *      Для всех
     *      Для жилого комплекса
     *      Для конкретного дома
     */
    public function actionForWhomNews($status) {
        
        // Получаем список всех домов
        $houses_list = Houses::getHousesList();
        
        if ($status == 'all') {
            echo '<option value>-</option>';
        } elseif ($status == 'house') {
            foreach ($houses_list as $house) {
                $full_adress = $house['houses_gis_adress'] . ', д. ' . $data['houses_number'];
                echo '<option value="' . $house['houses_id'] . '">' . $full_adress . '</option>';
            }
        }
    }
    
    /*
     * Запрос на удаление публикации
     */
    public function actionDeleteNews() {
        
        $news_id = Yii::$app->request->post('newsId');
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $news = News::findOne($news_id);
            $_advert = $news->isAdvert ? 'adverts' : 'news';
            
            if (!$news->delete()) {
                Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('success', ['message' => 'Новость ' . $news->news_title . ' была успешно удалена']);
        }
        return $this->redirect(['index', 'section' => $_advert]);        
    }
    
    /*
     * Запрос на удаление прикрепленного документа
     */
    public function actionDeleteFile() {
        
        $file_id = Yii::$app->request->post('fileId');
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $file = Image::findOne($file_id);
            if (!$file->delete()) {
                Yii::$app->session->setFlash('news-admin', [
                    'success' => false, 
                    'error' => 'Извините, при удалении документа произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз']);
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    
}
