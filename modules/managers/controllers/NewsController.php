<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\form\NewsForm;
    use app\models\News;
    use app\models\Rubrics;
    use app\models\Houses;
    use app\helpers\FormatHelpers;

/**
 * Новости
 */
class NewsController extends AppManagersController {
    
    /*
     * Новости, главная страница
     */
    public function actionIndex() {
        return $this->render('index');
    }
    
    /*
     * Создание новости
     * 
     * $status_publish array Статус размещения публикации
     * $rubrics array Тип публикации
     */
    public function actionCreate() {
        
        $model = new NewsForm();
        $status_publish = News::getStatusPublish();
        $notice = News::getArrayStatusNotice();
        $type_notice = News::getNoticeType();
        $rubrics = Rubrics::getArrayRubrics();
        $houses = [];
        
        return $this->render('form/create', [
            'model' => $model,
            'status_publish' => $status_publish,
            'notice' => $notice,
            'type_notice' => $type_notice,
            'rubrics' => $rubrics,
            'houses' => $houses,
        ]);
    }
    
    /*
     * Зависимый переключатель статуса публикации
     *      Для всех
     *      Для жилого комплекса
     *      Для конкретного дома
     */
    public function actionForWhomNews($status) {
        
        $current_house = Houses::getHousesList();
        
        if ($status == 0) {
            echo '<option value="-1">Для всех</option>';
        } elseif ($status == 1) {
            echo '<option value="0">Для жилого комплекса</option>';
        } elseif ($status == 2) {
            foreach ($current_house as $house) {
                $full_adress = \app\helpers\FormatHelpers::formatFullAdress(
                        $house['houses_town'], 
                        $house['houses_street'], 
                        $house['houses_number_house'], 
                        false, false) ;
                echo '<option value="' . $house['houses_id'] . '">' . $full_adress . '</option>';
            }
        }
        
    }
    
    
}
