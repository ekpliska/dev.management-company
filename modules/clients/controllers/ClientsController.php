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
    public function actionIndex($block = 'important_information') {

        // Получаем ID текущего лицевого счета
        $accoint_id = $this->_choosing;
        
        $estate_id = Yii::$app->userProfile->_user['estate_id'];
        $house_id = Yii::$app->userProfile->_user['house_id'];
        $flat_id = Yii::$app->userProfile->_user['flat_id'];
        
        switch ($block) {
            case 'important_information':
            case null: {
                break;
            }
            case 'special_offers': {
                break;
            }
            case 'house_news': {
                $news = News::getNewsByClients($estate_id, $house_id, $flat_id);
                break;
            }
        }
        return $this->render('index', [
            'news' => $news,
        ]);
    }
    
}
