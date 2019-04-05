<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\data\Pagination;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\News;
    use app\modules\clients\models\ImportantInformations;
    

/**
 * Собственник, Новостная лента
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
        
        return $this->render('index', [
            'living_space' => $living_space,
        ]);
        
    }
    
}
