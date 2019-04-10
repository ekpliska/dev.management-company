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
            'indications' => $this->getCountersIndication(),
            'payments' => $this->getPaymentsList(),
        ]);
        
    }
    
    /*
     * Получить теукщие показания приборов учета
     * по текущему лицевому счету
     * Для вкладки "Приборы учета", Профиль пользователя
     */
    private function getCountersIndication() {
        
        $account_number = $this->_current_account_number;
        
        // Формируем запрос для текущего расчетного перирода
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Номер месяца' => date('m'),
            'Год' => date('Y'),
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $indications = Yii::$app->client_api->getPreviousCounters($data_json);
        
        return $indications;
        
    }
    
    /*
     * Получить список оплаченных/не оплаченных квитанций
     */
    public function getPaymentsList() {
        
        // Получить номер текущего лицевого счета
        $account_number = $this->_current_account_number;
        // Получаем номер текущего месяца и год
        $current_period = date('Y-n');
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Период начало' => null,
            'Период конец' => $current_period,
        ];
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $receipts_lists = Yii::$app->client_api->getReceipts($data_json);
        
        return $receipts_lists;
    }
    
}
