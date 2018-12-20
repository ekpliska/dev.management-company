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
        $accoint_id = $this->_choosing;
        
        $account_info = PersonalAccount::getAccountInfo($accoint_id, $user_info->clientID);
        
        // Загуржаем модель добавления нового лицевого счета
        $model = new NewAccountForm();
        
        return $this->render('index', [
            'user_info' => $user_info,
            'account_info' => $account_info,
            'model' => $model,
        ]);
        
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
     * Страница "Квитанции ЖКУ"
     * receipts of housing and public utilities (receipts-of-hapu)
     */
    public function actionReceiptsOfHapu() {
        
        $user_info = $this->permisionUser();
        
        // Получить список всех лицевых счетов пользователя        
        $account_all = $this->_list;
        
        return $this->render('receipts-of-hapu', [
            'account_all' => $account_all,
        ]);
    }
    
    /*
     * Страница "Платежи"
     */
    public function actionPayments() {
        return $this->render('payments');
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
        
        // Статус кнопок управления "Ввод показаний", "Сохранить"
        $is_btn = true;
        // Получаем номер текущего месяца
        $current_month = date('n');
        // Получаем номер текущего года
        $current_year = date('Y');
        
        $user_info = $this->permisionUser();
        $account_id = $this->_choosing;
        $account_number = $this->_value_choosing;
        
        // Получаем список приборов учета с формированной заявкой на поверку счетчиков
        $counter_request = Counters::notVerified($account_id);
        
        // Получаем комментарии по приборам учета Собсвенника. Комментарий формирует Администратор системы
        $comments_to_counters = CommentsToCounters::getComments($account_id);
        
        // Формируем запрос в формате JSON на отрпавку по API
        $data = "{
                'Номер лицевого счета': '{$account_id}',
                'Номер месяца': '{$current_month}',
                'Год': '{$current_year}'
        }";
                
//        $indications = Yii::$app->client_api->getPreviousCounters($data);
        $indications = Yii::$app->params['current_indications'];

        $model_indication = new SendIndicationForm();
                
        return $this->render('counters', [
            'indications' => $indications,
            'comments_to_counters' => $comments_to_counters,
            'model_indication' => $model_indication,
            'is_btn' => $is_btn,
            'counter_request' => $counter_request,
        ]);
        
    }
    
    /*
     * Отправка показаний, валидация формы
     */
    public function actionSendIndications() {
        
        $model_indication = [new SendIndicationForm()];
        
        if (Yii::$app->request->isPost) {
            $models = [];
            $data = [];
            $t = Yii::$app->request->post($model_indication[0]->formName());
            
            foreach ($t as $counter_num => $post_data) {
                $newModel = new SendIndicationForm();
                $newModel->load($post_data);
                $models[$counter_num] = $newModel;
                $data[$counter_num] = $post_data;
            }
            
            if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
                if ($this->sendIndicationAPI($data)) {
                    Yii::$app->session->setFlash('success', ['message' => 'Показания приборов учета были переданы успешно']);
                } else {
                    Yii::$app->session->setFlash('error', ['message' => 'При передаче показаний возникла ошибка. Обновите страницу и повторите действие снова']);
                }
                return $this->redirect(['counters']);
            }
        }
        
        return $this->goHome();
        
    }
    
    /*
     * Отправка показаний приборов учета по API
     */
    private function sendIndicationAPI($data) {
        
        if (!is_array($data)) {
            return false;
        }
        
        $array_request['Приборы учета'] = [];
        
        foreach ($data as $key => $data) {
            $array['Регистрационный номер прибора учета'] = $key;
            $array['Дата снятия показания'] = date('Y-m-d');
            $array['Текущее показание'] = $data['current_indication'];
            $array_request['Приборы учета'][] = $array;
        }
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $result = Yii::$app->client_api->setCurrentIndications($data);
//        var_dump($result); die();
        
        if ($result['status'] == 'error' || $result['success'] == false ) {
            return false;
        }
        
        return true;
        
    }
    
    /*
     * Формирование заявки на платную услугу
     * Наименование услуги: Поверка приборов учета
     */
    public function actionCreatePaidRequest() {
        
        $account_id = Yii::$app->request->post('accountID');
        $counter_type = Yii::$app->request->post('typeCounter');
        $counter_num = Yii::$app->request->post('numCounter');
        
        Yii::$app->response->format = Response::FORMAT_JSON;        
        
        if (!is_numeric($account_id) && !is_string($counter_type || !is_numeric($counter_num))) {
            return ['success' => false];
        }
        
        if (Yii::$app->request->isAjax) {
            
            $result = $new_request = PaidServices::automaticRequest($account_id, [
                'type' => 'Поверка',
                'value' => 'Тип прибора учета: ' . $counter_type . 'Регистрационный номер прибора учета' . $counter_num,
            ]);
            
            if (!$result) {
                return ['success' => false];
            }
            
            $counter = Counters::setRequestStatus($counter_num);
            
            return ['success' => true, 'request_number' => $result];
        }
        return ['success' => false];
    }
    
    public function actionFindIndications($month, $year) {
        
        $account_id = $this->_choosing;
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!is_numeric($month) || !is_numeric($year) || !isset($month, $year)) {
            return ['success' => false];
        }
        
        if (Yii::$app->request->isPost) {
            
            // Формируем запрос в формате JSON на отрпавку по API
            $data = "{
                    'Номер лицевого счета': '{$account_id}',
                    'Номер месяца': '{$month}',
                    'Год': '{$year}'
            }";
            $indications = Yii::$app->client_api->getPreviousCounters($data);
            
            $data = $this->renderPartial('data/grid-counters', ['indications' => $indications['indications']]);
            return ['success' => true, 'result' => $indications['indications']];
        }
        return ['success' => false];
        
    }
    
    /*
     * Создание лицевого счета собсвенника
     */
    public function actionCreateAccount($client_id) {
        
        $model = new NewAccountForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->createAccount()) {
                Yii::$app->session->setFlash('success', ['message' => 'Лицевой счет был успешно создан']);
            } else {
                Yii::$app->session->setFlash('error', ['message' => 'При создании лицевого счета произошла ошибка. Обновите страницу и повторите действие снова']);
            }
            return $this->redirect('index');
        }
        
        Yii::$app->session->setFlash('error', ['message' => 'При создании лицевого счета произошла ошибка. Обновите страницу и повторите действие снова']);
        return $this->redirect('index');
    }
    
    
}
