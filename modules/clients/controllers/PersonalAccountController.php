<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    use yii\base\Model;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\PersonalAccount;
    use app\modules\clients\models\form\NewAccountForm;
    use app\models\CommentsToCounters;
    use app\modules\clients\models\form\SendIndicationForm;
    use app\models\PaidServices;
    use app\models\Counters;

/**
 * Контроллер по работе с разделом "Лицевой счет"
 */
class PersonalAccountController extends AppClientsController {

    /*
     * Главная страница
     * 
     * @param integer $accoint_id Значение ID лицевого счета из глобального dropDownList (хеддер)
     * @param array $account_all Список всех лицевых счетов Собственника
     * @param array $account_info Информация по лицевому счету Собственника
     */
    public function actionIndex() {
        
        $user_info = $this->permisionUser();
        $accoint_id = $this->_current_account_id;
        
        $account_info = PersonalAccount::getAccountInfo($accoint_id, $user_info->clientID);
        
        // Загружаем модель добавления нового лицевого счета
        $model = new NewAccountForm();
        
        return $this->render('index', [
            'user_info' => $user_info,
            'account_info' => $account_info,
            'model' => $model,
        ]);
        
    }
    
    /*
     * Страница "Квитанции ЖКУ"
     * receipts of housing and public utilities (receipts-of-hapu)
     */
    public function actionReceiptsOfHapu() {
        
        $user_info = $this->permisionUser();
        
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
        
        return $this->render('receipts-of-hapu', [
            'account_number' => $account_number,
            'receipts_lists' => $receipts_lists ? $receipts_lists : null,
        ]);
    }
    
    /*
     * Страница "Платежи"
     */
    public function actionPayments() {
        
        $user_info = $this->permisionUser();
        
        // Получить номер текущего лицевого счета
        $account_number = $this->_current_account_number;
        
        // Получаем номер текущего месяца и год
        $current_period = date('Y-m-d');
        
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Период начало' => null,
            'Период конец' => $current_period,
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        
        $payments_lists = Yii::$app->client_api->getPayments($data_json);
        
        return $this->render('payments', [
            'account_number' => $account_number,
            'payments_lists' => $payments_lists ? $payments_lists : null,
        ]);
    }
    
    /*
     * Страница "Платеж" (форма оплаты)
     */
    public function actionPayment() {
        
        $user_info = $this->permisionUser();
        
        
        return $this->render('payment', [
            'user_info' => $user_info,
        ]);
    }
    
    /*
     * Страница "Показания приборов учета"
     * 
     * При каждом обрашении к странице Показания приборов учета,
     * происходит отправка запроса по API на получение актуальных показаний
     */
    public function actionCounters() {
        
        // Статус текущих показаний
        $is_current = true;
        
        $account_id = $this->_current_account_id;
        $account_number = $this->_current_account_number;
        
        // Получаем список зявок сформированных автоматически на поверу приборов учета
        $auto_request = PaidServices::notVerified($account_id);
        
        // Получаем комментарии по приборам учета Собсвенника. Комментарий формирует Администратор системы
        $comments_to_counters = CommentsToCounters::getComments($account_id);

        // Формируем запрос для текущего расчетного перирода
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Номер месяца' => date('m'),
            'Год' => date('Y'),
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $indications = Yii::$app->client_api->getPreviousCounters($data_json);
        
        return $this->render('counters', [
            'indications' => $indications ? $indications : null,
            'comments_to_counters' => $comments_to_counters,
            'is_current' => $is_current,
            'auto_request' => $auto_request,
        ]);
        
    }    
    
    /*
     * Отправка показаний приборов учета
     */
    public function actionSendIndication($counter, $indication) {
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $array = [
                "ID" => $counter,
                "Дата снятия показания" => date('Y-m'),
                "Текущее показание" => $indication,
            ];
    
            $array_request['Приборы учета'] = [
                $array,
            ];
            
            $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
            
            $result = Yii::$app->client_api->setCurrentIndications($data_json);
            
            if (!$result) {
                return ['success' => false];
            }
            
            return [
                'success' => true,
            ];
        }
        
    }
    
    /*
     * Формирование автоматической заявки на платную услугу
     * Наименование услуги: Поверка приборов учета
     */
    public function actionCreatePaidRequest() {
        
        $account_id = Yii::$app->request->post('accountID');
        $counter_type = Yii::$app->request->post('typeCounter');
        $counter_id = Yii::$app->request->post('idCounter');
        
        Yii::$app->response->format = Response::FORMAT_JSON;        
        
        if (!is_numeric($account_id) && !is_string($counter_type || !is_numeric($counter_id))) {
            return ['success' => false];
        }
        
        if (Yii::$app->request->isAjax) {
            
            $result = $new_request = PaidServices::automaticRequest($account_id, 'Поверка', $counter_type, $counter_id);
            
            if (!$result) {
                return ['success' => false];
            }
            
            return ['success' => true, 'request_number' => $result];
        }
        return ['success' => false];
    }
    
    public function actionFindIndications($month, $year) {
        
        $account_number = $this->_current_account_number;
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!is_numeric($month) || !is_numeric($year) || !isset($month, $year)) {
            return ['success' => false];
        }
        
        if (Yii::$app->request->isPost) {
            
            // Формируем запрос в массиве
            $array_request = [
                'Номер лицевого счета' => $account_number,
                'Номер месяца' => $month,
                'Год' => $year,
            ];
        
            // Преобразуем массив в формат JSON
            $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
            
            $indications = Yii::$app->client_api->getPreviousCounters($data_json);
            
            $data = $this->renderPartial('data/grid-counters', [
                'indications' => $indications ? $indications : null,
                'form' => null,
                'auto_request' => null,
                'is_current' => false,
                'model_indication' => null,
            ]);
            return ['success' => true, 'result' => $data];
        }
        return ['success' => false];
        
    }
    
    /*
     * Общий метод валидации форм раздела Лицевой счет
     */
    public function actionValidateForm($form) {
        
        if ($form == null) {
            throw new NotFoundHttpException('Ошибка передалчи параметров. Вы обратились к несуществующей странице');
        }
        
        switch ($form) {
            case 'NewAccountForm':
                $model = new NewAccountForm();
                break;
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        
    }    
    
    /*
     * Создание лицевого счета Собсвенника
     */
    public function actionCreateAccount() {
        
        $model = new NewAccountForm();
        
        // Получаем текущий лицевой счет
        $old_account_id = $this->_current_account_id;
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->createAccount($old_account_id)) {
                Yii::$app->session->setFlash('success', ['message' => 'Лицевой счет был успешно создан']);
                return $this->redirect('index');
            }
        }
        
        Yii::$app->session->setFlash('error', ['message' => 'При создании лицевого счета произошла ошибка. Обновите страницу и повторите действие заново']);
        return $this->redirect('index');
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
                $type . '_lists' => $results ? $results : null,
                'account_number' => $account_number]);
            
            return [
                'success' => true,
                'data_render' => $data_render,
                'results' => $results,
                'date_start' => $date_start,
                'date_end' => $date_end,
            ];
        }
        
        return ['success' => false];
        
    }
    
    
}
