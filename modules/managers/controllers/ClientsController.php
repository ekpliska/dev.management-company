<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\Response;
    use yii\web\NotFoundHttpException;
    use yii\web\UploadedFile;
    use yii\widgets\ActiveForm;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\Clients;
    use app\modules\managers\models\User;
    use app\models\PersonalAccount;
    use app\models\Rents;
    use app\modules\managers\models\AddRent;
    use app\modules\managers\models\form\CounterIndicationsForm;
    use app\models\CommentsToCounters;
    use app\modules\managers\models\searchForm\searchClients;

/**
 * Клиенты
 */
class ClientsController extends AppManagersController {
    
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
        $user_info = User::findByClientId($client_id);
        
        $user_info->scenario = User::SCENARIO_EDIT_CLIENT_PROFILE;
        
        $add_rent = new AddRent();
        
        if ($account_info->personal_rent_id) {
            $is_rent = true;
            $edit_rent = Rents::findOne(['rents_id' => $account_info->personal_rent_id]);
        } else {
            $is_rent = false;
            $edit_rent = null;
        }
        
        if ($client_info == null || $account_info == null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        if ($user_info->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($user_info, 'user_photo');
            $user_info->uploadPhoto($file);
            $this->updateClientInfo($client_info, $edit_rent);
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return $this->render('view-client', [
            'is_rent' => $is_rent,
            'client_info' => $client_info,
            'user_info' => $user_info,
            'account_choosing' => $account_info,
            'list_account' => $list_account,
            'rent_info' => $edit_rent,
            'add_rent' => $add_rent,
        ]);
        
    }
    
    public function updateClientInfo($client_info, $edit_rent) {
        
        // Если переключатель Арендатор пришел из пост
        if (isset($_POST['is_rent'])) {
            if ($client_info->load(Yii::$app->request->post())) {
                
                $add_rent = Yii::$app->request->post('AddRent');
                
                // Сохраняем данные существующего арендатора
                if ($edit_rent !== null) {
                    if ($edit_rent->load(Yii::$app->request->post())) {
                        // Если есть ошибки валидации
                        if ($edit_rent->hasErrors()) {
                            Yii::$app->session->setFlash('profile-admin-error');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                        $edit_rent->save();
                    }
                }
                
                if ($add_rent !== null) {
                    $rent = new AddRent();
                    if ($rent->load(Yii::$app->request->post())) {
                        if ($rent->hasErrors()) {
                            Yii::$app->session->setFlash('profile-admin-error');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                        $rent->addNewRent();
                    }
                }
                
                Yii::$app->session->setFlash('profile-admin');
                $client_info->save();
            }
        } else {
            // Если переключатель Арендатор, не пришли из пост, сохраняем данные только собственника
            if ($client_info->load(Yii::$app->request->post())) {
                Yii::$app->session->setFlash('profile-admin');
                $client_info->save();
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
//        echo '<pre>'; var_dump($counters_lists_api); die();

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
        
        $date_start = Yii::$app->formatter->asDate($date_start, 'Y-M-d');
        $date_end = Yii::$app->formatter->asDate($date_end, 'Y-M-d');
                
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
//                    $results = Yii::$app->client_api->getReceipts($data_json);
                    $results = Yii::$app->params['Квитанции ЖКУ_4'];
                    break;
                case 'payments':
//                    $results = Yii::$app->client_api->getReceipts($data_json);
                    $results = Yii::$app->params['Платежи'];
                    break;
                default:
                    $results['status'] == 'error';
                    break;
            }
            
            if ($results['status'] == 'error') {
                return ['success' => false];
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
     * Валидация и отправка формы "Текущие показания приборов учета"
     */
    public function actionSendIndicationForm() {
        
        $model_indication = [new CounterIndicationsForm()];
        $model_notice = new CommentsToCounters();
        
        
        if (Yii::$app->request->isPost) {
        
            $post_data = Yii::$app->request->post($model_indication[0]->formName());
            $models = [];

            foreach ($post_data as $key => $data) {
                $model = new CounterIndicationsForm();
                $model->load($data);
                $models[$key] = $model;
            }

            if (\yii\base\Model::loadMultiple($models, Yii::$app->request->post()) && \yii\base\Model::validateMultiple($models)) {
                echo 'ok'; die();
            }

                echo 'error'; die();
        }
    }
    
}
