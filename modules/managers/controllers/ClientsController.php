<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\Response;
    use yii\web\NotFoundHttpException;
    use yii\widgets\ActiveForm;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\Clients;
    use app\modules\managers\models\User;
    use app\models\PersonalAccount;
    use app\models\Rents;
    use app\modules\managers\models\searchForm\searchClients;
    use app\models\PaidServices;
    use app\models\CommentsToCounters;
    use app\modules\managers\models\form\CommentToCounterForm;

/**
 * Клиенты
 */
class ClientsController extends AppManagersController {
    
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['ClientsView']
                    ],
                    [
                        'actions' => [
                            'view-client', 
                            'block-client',
                            'block-client-in-view',
                            'check-account',
                            'receipts-of-hapu',
                            'payments',
                            'counters',
                            'account-info',
                            'clients-info',
                            'search-data-on-period',
                            'delete-client',
                            'find-indications',
                            'send-indication',
                            'create-paid-request',
                            'create-notification',
                            'delete-note-counters'],
                        'allow' => true,
                        'roles' => ['ClientsEdit']
                    ],
                ],
            ],
        ];
    }
    
    /*
     * Главная страница
     * 
     * Собственники
     */
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
        $user_info = User::findOne(['user_client_id' => $client_id]);
        
        if ($account_info->personal_rent_id) {
            $is_rent = true;
            $edit_rent = Rents::findOne(['rents_id' => $account_info->personal_rent_id]);
        } else {
            $is_rent = false;
            $edit_rent = null;
        }
        
        if ($client_info == null || $account_info == null) {
            throw new NotFoundHttpException(404);
        }
        
        if ($user_info->load(Yii::$app->request->post()) 
                && $client_info->load(Yii::$app->request->post()) && $client_info->load(Yii::$app->request->post())) {
            
            if (!$user_info->validate()) {
                $error_message = reset($user_info->getFirstErrors());
                Yii::$app->session->setFlash('error', ['message' => "Ошибка обновления профиля пользователя. {$error_message}"]);
                return $this->redirect(Yii::$app->request->referrer);
            }
            
            $user_info->save();
            $this->updateClientInfo($client_info, $edit_rent);
            
        }

        return $this->render('view-client', [
            'is_rent' => $is_rent,
            'client_info' => $client_info,
            'user_info' => $user_info,
            'account_choosing' => $account_info,
            'list_account' => $list_account,
            'rent_info' => $edit_rent,
        ]);
        
    }
    
    /*
     * Обновление данных собственника, арендатора
     */
    private function updateClientInfo($client_info, $edit_rent) {
        
        // Если переключатель Арендатор пришел из пост
        if (isset($_POST['is_rent'])) {
            if ($client_info->load(Yii::$app->request->post()) && $client_info->validate()) {
                // Сохраняем данные существующего арендатора
                if (!empty($edit_rent)) {
                    if ($edit_rent->load(Yii::$app->request->post())) {
                        // Если есть ошибки валидации
                        if (!$edit_rent->save()) {
                            $error_message = reset($edit_rent->getFirstErrors());
                            Yii::$app->session->setFlash('error', ['message' => "Ошибка обновления профиля пользователя. {$error_message}"]);
                            return false;
                        }
                    }
                }
                Yii::$app->session->setFlash('success', ['message' => "Учетная запись собсвенника {$client_info->fullName} успешно обновлена."]);
                $client_info->save();
                return true;
            }
        } else {
            // Если переключатель Арендатор, не пришли из пост, сохраняем данные только собственника
            if ($client_info->load(Yii::$app->request->post())) {
                Yii::$app->session->setFlash('success', ['message' => "Учетная запись собсвенника {$client_info->fullName} успешно обновлена."]);
                $client_info->save();
                return true;
            }
        }
        
    }
    
    /*
     * Блокировать/Разблокировать Собственника
     * 
     * На главной странице, для талицы
     */
    public function actionBlockClient() {
                
        $client_id = Yii::$app->request->post('clientId');
        $status = Yii::$app->request->post('statusClient');
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $user_info = User::findByClientId($client_id);
            $user_info->block($client_id, $status);
            return ['status' => $status, 'client_id' => $client_id];
        }
        
        return ['status' => false];
    }

    /*
     * Блокировать/Разблокировать Собственника
     * 
     * На странице просмотра информации о Собственнике
     */
    public function actionBlockClientInView() {
                
        $user_id = Yii::$app->request->post('userId');
        $status = Yii::$app->request->post('statusClient');
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $user_info = User::findByID($user_id);
            $user_info->blockInView($user_id, $status);
            return ['status' => $status, 'user_id' => $user_id];
        }
        
        return ['status' => false];
    }
    
    /*
     * Фильтр выбора лицевого счета
     * 
     * dropDownList Лицевой счет
     */
    public function actionCheckAccount() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax) {
            
            // Из пост запроса получаем ID лицевого счета и собственника
            $account_id = Yii::$app->request->post('dataAccount');
            $client_id = Yii::$app->request->post('dataClient');
            
            // Ищем арендатора, закрепленного за указанным лицевым счетом
            $model = PersonalAccount::findByRent($account_id, $client_id);
            
            /*
             * Если арендатор существует, генерирурем для него модель
             */
            if (!empty($model->personal_rent_id)) {
                $rent_info = Rents::findOne(['rents_id' => $model->personal_rent_id]);
                if ($rent_info) {
                    $is_rent = true;
                }
            } else {
                $rent_info = [];
            }
            
            $data = $this->renderPartial('_form/rent-view', [
                'form' => ActiveForm::begin(),
                'rent_info' => $rent_info, 
            ]);
            
            return ['status' => true, 'data' => $data, 'is_rent' => $is_rent];
            
        }
        return ['status' => false];
        
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
            'receipts_lists' => $receipts_lists ? $receipts_lists : null,
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
            'payments_lists' => $payments_lists ? $payments_lists : null,
        ]);
        
    }

    /*
     * Собственник, Приборы учета
     */
    public function actionCounters($client_id, $account_number) {
        
        $info = $this->getClientsInfo($client_id, $account_number);
        
        // Статус текущих показаний
        $is_current = true;
        // Получаем список зявок сформированных автоматически на поверу приборов учета
        $auto_request = PaidServices::notVerified($info['account_info']->account_id);
        
        // Комментарии к приборам учета
        $model_comment = new CommentToCounterForm();
        $comment_counter = CommentsToCounters::findOne(['account_id' => $info['account_info']->account_id]);
        if (Yii::$app->request->isPost) {
            if ($comment_counter->load(Yii::$app->request->post()) && $comment_counter->validate()) {
                $comment_counter->save();
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Номер месяца' => date('m'),
            'Год' => date('Y'),
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $counters_lists_api = Yii::$app->client_api->getPreviousCounters($data_json);

        return $this->render('counters', [
            'client_info' => $info['client_info'],
            'account_choosing' => $info['account_info'],
            'user_info' => $info['user_info'],
            'list_account' => $info['list_account'],
            'counters_lists' => $counters_lists_api ? $counters_lists_api : null,
            'is_current' => $is_current,
            'auto_request' => $auto_request,
            'comment_counter' => $comment_counter,
            'model_comment' => $model_comment,
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

        if (empty($client_info) || empty($account_info) || empty($user_info) || empty($list_account)) {
            throw new NotFoundHttpException(404);
        }
        
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
     * Запрос на удаление учетной записи собсвенника
     */
    public function actionDeleteClient() {
                
        $client_id = Yii::$app->request->post('clientId');
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $client_info = Clients::findById($client_id);
            $full_name = $client_info->fullName;
            if (!$client_info->delete()) {
                Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления учетной записи собсвенника. Обновите страницу и повторите действие еще раз.']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('success', ['message' => "Учетная запись собсвенника {$full_name} успешно удалена из системы."]);
            return $this->redirect(['clients/index']);
        }
        
        Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления учетной записи собсвенника. Обновите страницу и повторите действие еще раз.']);
        return $this->redirect(Yii::$app->request->referrer);
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
                'auto_request' => null,
                'is_current' => false,
                'model_indication' => null,
            ]);
            return ['success' => true, 'result' => $data];
        }
        return ['success' => false];
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
            $result = PaidServices::automaticRequest($account_id, 'Поверка', $counter_type, $counter_id);
            if (!$result) {
                return ['success' => false];
            }
            return ['success' => true, 'request_number' => $result];
        }
        return ['success' => false];
    }
    
    /*
     * Добавление нового уведомления для приборов учета
     */
    public function actionCreateNotification($account_id) {
        
        $model = new CommentToCounterForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save($account_id);
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /*
     * Удаление уведомления
     */
    public function actionDeleteNoteCounters($id) {
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $note = CommentsToCounters::findOne($id);
            if ($note->delete()) {
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
    }
    
}
