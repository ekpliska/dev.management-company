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
        
        return $this->render('index');
    }
    
}
