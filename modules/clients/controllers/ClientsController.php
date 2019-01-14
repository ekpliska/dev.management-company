<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\News;
    use app\modules\clients\models\ImportantInformations;
    

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
        $account_id = $this->_current_account_id;
        // Получаем массив содержащий ID ЖК, ID дома, ID квартиры, номер подъезда
        $living_space = Yii::$app->userProfile->getLivingSpace($account_id);
        
        switch ($block) {
            case 'important_information':
            case null: {
                $info = new ImportantInformations();
                $news = $info->informations($living_space);
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
