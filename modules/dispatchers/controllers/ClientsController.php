<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use yii\web\Response;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\models\Clients;
    use app\models\PersonalAccount;
    use app\models\User;
    use app\models\Rents;
    use app\modules\dispatchers\models\searchForm\searchClients;
    use app\models\SiteSettings;

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
        
        $path_to_receipts = SiteSettings::getReceiptsUrl();
        
        return $this->render('receipts-of-hapu', [
            'client_info' => $info['client_info'],
            'account_choosing' => $info['account_info'],
            'user_info' => $info['user_info'],
            'list_account' => $info['list_account'],
            'account_number' => $account_number,
            'receipts_lists' => $receipts_lists,
            'path_to_receipts' => $path_to_receipts,
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
            'payments_lists' => $payments_lists,
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
            'counters_lists' => $counters_lists_api,
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
    
    /*
     * Запрос на получение квитанций по заданному лицевому счету и диапазону
     * 
     * Формируем запрос, преобразуем в JSON, отправляем по API:
     * $data_array = [
     *      "Номер лицевого счета" => $account_number,
     *      "Период начало" => $date_start,
     *      "Период конец" => $date_end
     * ]
     * 
     */
    public function actionSearchDataOnPeriod($account_number, $date_start, $date_end, $type) {
        
        $date_start = Yii::$app->formatter->asDate($date_start, 'YYYY-MM-d');
        $date_end = Yii::$app->formatter->asDate($date_end, 'YYYY-MM-d');
                
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!is_numeric($account_number)) {
            return ['success' => false];
        }
        
        $data_array = [
            'Номер лицевого счета' => $account_number,
            'Период начало' => $date_start,
            'Период конец' => $date_end
        ];        
        $data_json = json_encode($data_array, JSON_UNESCAPED_UNICODE);
        
        if (Yii::$app->request->isPost) {
            
            switch ($type) {
                case 'receipts':
                    $results = Yii::$app->client_api->getReceipts($data_json);
                    break;
                case 'payments':
                    $results = Yii::$app->client_api->getPayments($data_json);
                    break;
                default:
                    $results['status'] == 'error';
                    break;
            }
            
            $data_render = $this->renderPartial('data/' . $type . '-lists', [
                $type . '_lists' => $results,
                'account_number' => $account_number]);
            
            return [
                'success' => true,
                'data_render' => $data_render,
                'date_start' => $date_start,
                'date_end' => $date_end,
            ];
        }
        
        return ['success' => false];
        
    }
    
    /*
     * Запрос поиска предыдущих показаний приборов учета
     */
    public function actionFindIndications($month, $year, $account) {

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {

            // Формируем запрос в массиве
            $array_request = [
                'Номер лицевого счета' => $account,
                'Номер месяца' => $month,
                'Год' => $year,
            ];

            // Преобразуем массив в формат JSON
            $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);

            $indications = Yii::$app->client_api->getPreviousCounters($data_json);

            $data = $this->renderPartial('data/counters-lists', [
                'counters_lists' => $indications ? $indications : null,
            ]);
            return ['success' => true, 'result' => $data];
        }
        return ['success' => false];
    }
    
    /*
     * Получение URL для квитанции
     */
    public function actionGetReceiptPdf() {
        
        $house_id = Yii::$app->request->post('house');
        $period = Yii::$app->request->post('period');
        $account = Yii::$app->request->post('account');
        
        $path_to_receipts = SiteSettings::getReceiptsUrl();
        $path_url = $path_to_receipts . "{$house_id}/{$period}/{$account}.pdf";
        $headers = @get_headers($path_url);
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            if (strpos($headers[0], '200')) {
                return ['success' => true, 'url' => $path_url];
            }
            return ['success' => false];
        }
        
        return ['success' => false];
        
    }
    
}
