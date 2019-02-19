<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\models\Clients;
    use app\models\PersonalAccount;
    use app\models\User;
    use app\models\Rents;
    use app\modules\dispatchers\models\searchForm\searchClients;

/**
 * Профиль собственника
 */
class ClientsController extends AppDispatchersController {
    
    public function actionIndex() {
        
        $model = new searchClients();
        
        $client_list = $model->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'model' => $model,
            'client_list' => $client_list,
        ]);
        
    }
    
    /*
     * Просмотр сведений о Собственнике
     * 
     * @param integer $is_rent Переключатель наличия арендатора
     * @param array $client_info Информация о Собственнике
     * @param array $account_number Текущий лицевой счет
     * @param array $user_info Информация об учетной записи Собственника (Пользователь)
     */
    public function actionViewClient($client_id, $account_number) {
        
        $is_rent = false;
        
        $client_info = Clients::findById($client_id);
        $account_info = PersonalAccount::findByNumber($account_number);
        $list_account = PersonalAccount::findByClient($client_id, true);
        $user_info = User::findByClientId($client_id);
        
        if ($account_info->personal_rent_id) {
            $is_rent = true;
            $rent_info = Rents::findOne(['rents_id' => $account_info->personal_rent_id]);
        } else {
            $is_rent = false;
            $rent_info = null;
        }
        
        if ($client_info == null || $account_info == null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        return $this->render('view-client', [
            'is_rent' => $is_rent,
            'client_info' => $client_info,
            'user_info' => $user_info,
            'account_choosing' => $account_info,
            'list_account' => $list_account,
            'rent_info' => $rent_info,
        ]);
        
    }
    
    /*
     * Собственник, Квитанции ЖКУ
     * 
     * Формируем запрос, преобразуем в JSON, отправляем по API:
     * $data_array = [
     *      "Номер лицевого счета" => $account_number,
     *      "Период начало" => null,
     *      "Период конец" => null
     * ]
     * 
     * Если период начала и период конца null, то возвращает список всех квитанций
     * 
     */
    public function actionReceiptsOfHapu($client_id, $account_number) {
        
        $info = $this->getClientsInfo($client_id, $account_number);
        
        // Получаем номер текущего месяца и год
        $current_period = date('Y-m');
        
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Период начало' => null,
            'Период конец' => $current_period,
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        
        $receipts_lists = Yii::$app->client_api->getReceipts($data_json);
        
        return $this->render('receipts-of-hapu', [
            'client_info' => $info['client_info'],
            'account_choosing' => $info['account_info'],
            'user_info' => $info['user_info'],
            'list_account' => $info['list_account'],
            'account_number' => $account_number,
            'receipts_lists' => $receipts_lists['receipts'],
        ]);
        
    }
    
    /*
     * Собственник, Платежи
     * 
     * Формируем запрос, преобразуем в JSON, отправляем по API:
     * $data_array = [
     *      "Номер лицевого счета" => $account_number,
     *      "Период начало" => null,
     *      "Период конец" => null
     * ]
     * 
     * Если период начала и период конца null, то возвращает список всех квитанций
     * 
     */
    public function actionPayments($client_id, $account_number) {
        
        $info = $this->getClientsInfo($client_id, $account_number);
        
        // Получаем номер текущего месяца и год
        $current_period = date('Y-m');
        
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Период начало' => null,
            'Период конец' => $current_period,
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        
        $payments_lists = Yii::$app->client_api->getPayments($data_json);
        
        return $this->render('payments', [
            'client_info' => $info['client_info'],
            'account_choosing' => $info['account_info'],
            'user_info' => $info['user_info'],
            'list_account' => $info['list_account'],
            'account_number' => $account_number,
            'payments_lists' => $payments_lists['payments'],
        ]);
        
    }
    
    /*
     * Собственник, Приборы учета
     * 
     * Формируем запрос, преобразуем в JSON, отправляем по API:
     * $data_array = [
     *      "Номер лицевого счета" => $account_number,
     *      "Номер месяца" => null,
     *      "Год" => null
     * ]
     * 
     * Если период начала и период конца null, то возвращает список всех квитанций
     * 
     */
    public function actionCounters($client_id, $account_number) {
        
        $info = $this->getClientsInfo($client_id, $account_number);
        
        // Получаем номер текущего месяца
        $current_month = date('m');
        // Получаем номер текущего года
        $current_year = date('Y');
        
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Номер месяца' => $current_month,
            'Год' => $current_year,
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $counters_lists_api = Yii::$app->client_api->getPreviousCounters($data_json);

        return $this->render('counters', [
            'client_info' => $info['client_info'],
            'account_choosing' => $info['account_info'],
            'user_info' => $info['user_info'],
            'list_account' => $info['list_account'],
            'counters_lists' => $counters_lists_api['indications'],
        ]);
        
    }
    
    /*
     * Собственник, Общая информация по лицевому счету
     */
    public function actionAccountInfo($client_id, $account_number) {
        
        $info = $this->getClientsInfo($client_id, $account_number);
        
        return $this->render('account-info', [
            'client_info' => $info['client_info'],
            'account_choosing' => $info['account_info'],
            'user_info' => $info['user_info'],
            'list_account' => $info['list_account'],
        ]);
        
    }
    
    /*
     * Получение всех данных о собственнике
     */
    protected function getClientsInfo($client_id, $account_number) {
        
        $client_info = Clients::findById($client_id);
        $account_info = PersonalAccount::findByNumber($account_number);
        $user_info = User::findByClientId($client_id);
        $list_account = PersonalAccount::findByClient($client_id, true);

        return [
            'client_info' => $client_info,
            'account_info' => $account_info,
            'user_info' => $user_info,
            'list_account' => $list_account,
        ];
        
    }
    
}
