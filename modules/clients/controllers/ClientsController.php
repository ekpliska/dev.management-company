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
        // Получаем массив содержащий ID ЖК, ID дома, ID квартиры, номер подъезда
        $living_space = Yii::$app->userProfile->getLivingSpace($accoint_id);
        
        switch ($block) {
            case 'important_information':
            case null: {
                break;
            }
            case 'special_offers': {
                $news = News::getNewsByClients($living_space, true);
                break;
                break;
            }
            case 'house_news': {
                $news = News::getNewsByClients($living_space, false);
                break;
            }
        }
        return $this->render('index', [
            'news' => $news,
        ]);
    }
    
}
