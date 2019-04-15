<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Response;
    use yii\web\UploadedFile;
    use yii\widgets\ActiveForm;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\User;
    use app\modules\clients\models\ClientsRentForm;
    use app\models\Rents;
    use app\models\PersonalAccount;
    use app\modules\clients\models\ChangePasswordForm;
    use app\modules\clients\models\ChangeMobilePhone;
    use app\models\SmsOperations;
    use app\modules\clients\models\form\SMSForm;
    use app\modules\clients\models\form\NewAccountForm;
    use app\models\PaidServices;
    use app\models\CommentsToCounters;
    
    

/**
 * Default controller for the `clients` module
 */
class ProfileController extends AppClientsController
{
    // Флаг наличия арендатора у собственника
    public $_is_rent = false;

    /**
     * Главная страница
     * 
     * @param array $user_info Учетная запись пользователя
     */
    public function actionIndex() {
        
        $user_info = $this->permisionUser();
        $user = $user_info->_model;
        
        $accoint_id = $this->_current_account_id;
        
        $account_info = PersonalAccount::getAccountInfo($accoint_id, $user_info->clientID);
        
        // Загружаем модель добавления нового лицевого счета
        $model = new NewAccountForm();
        
        // Загружаем модель добаления нового арендатора
        $add_rent = new ClientsRentForm(['scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION]);
        // Данные Арендатора
        $rent_info = Rents::find()->where(['rents_id' => $account_info['personal_rent_id']])->one();
        if ($rent_info) {
            if ($rent_info->load(Yii::$app->request->post()) && $rent_info->validate()) {
                if (!$rent_info->save()) {
                    Yii::$app->session->setFlash('error', ['message' => 'При обновлении данных арендатора произошла ошибка. Обновите страницу и повторите действие заново']);
                    return $this->redirect('index');
                }
                Yii::$app->session->setFlash('success', ['message' => 'Личная информация вашего арендатора была успешно обновлена']);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        // Полуаем данные по платежам, по текущему лиыевому счету
        $payment_history = $this->getPaymentsHistory();
        // Полуаем данные по приблрам учета, по текущему лицевому счету
        $counters_indication = $this->getCountersIndication();
        
        return $this->render('index', [
            'user' => $user,
            'account_info' => $account_info,
            'model' => $model,
            'add_rent' => $add_rent,
            'rent_info' => $rent_info,
            'payment_history' => $payment_history,
            'counters_indication' => $counters_indication,
        ]);
        
    }
    
    /*
     * Валидация формы добавления лицевого счета
     */
    public function actionValidateForm($form) {
        
        if ($form == null) {
            throw new NotFoundHttpException('Ошибка передачи параметров. Вы обратились к несуществующей странице');
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
     * Удаление учетной записи арендатора с портала
     */
    public function actionDeleteRentProfile() {
        
        $rent_id = Yii::$app->request->post('rentsId');
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $rent = Rents::findOne($rent_id);
            if (!$rent->delete()) {
                return $this->goHome();
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return ['status' => false];
        
    }
    
    /*
     * Проверка валидации формы добавление нового Арендатора
     */
    public function actionValidateRentForm() {
        
        $model = new ClientsRentForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
    }
    
    /*
     * Добавление учетной записи Арендатора
     */
    public function actionCreateRentForm($client) {
        
        $account = $this->_current_account_id;
        
        if ($client == null || $account == null) {
            return 'Ошибка отправки формы';
        }
        
        $model = new ClientsRentForm(['scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION]);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!$model->saveRentToUser($client, $account)) {
                Yii::$app->session->setFlash('error', ['message' => 'Ошибка добавления арендатора. Обновите страницу и повторите действие заново']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('success', ['message' => 'Учетная запись арендатора была успешно создана']);
            return $this->redirect(Yii::$app->request->referrer);
        }
        
    }
    
    
    /*
     * Получить историю платежей по текущему лицевому счету
     * Для вкладки "Платежи", Профиль пользователя
     */
    private function getPaymentsHistory() {
        
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
        
        return $payments_lists;
        
    }
    
    /*
     * Получить теукщие показания приборов учета
     * по текущему лицевому счету
     * Для вкладки "Приборы учета", Профиль пользователя
     */
    private function getCountersIndication() {
        
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
        
        return $indications;
        
    }
    
    /*
     * Раздел - Настройки профиля
     * @param array $model_password Модель смены пароля учетной записи
     */
    public function actionSettings() {
        
        // Проверяем время существования куки
        $this->hasCookieSMS();
        
        $user_info = $this->permisionUser();
        $user = $user_info->_model;
        
        // Загружаем модель смены пароля
        $model_password = new ChangePasswordForm($user);
        // Модель на смену номера мобильного телефона
        $model_phone = new ChangeMobilePhone($user);        
        
        // Модель на ввод СМС кода
        $sms_model = new SMSForm($user);
        
        // Получаем статус СМС запроса
        $is_change_password = SmsOperations::findByUserIDAndType($user_info->userID, SmsOperations::TYPE_CHANGE_PASSWORD);
        $is_change_phone = SmsOperations::findByUserIDAndType($user_info->userID, SmsOperations::TYPE_CHANGE_PHONE);

        if ($model_password->load(Yii::$app->request->post()) && $model_password->validate()) {
            // Если данные успешно провалидированы, то устанавливаем куку времени на смену пароля
            if ($model_password->checkPassword()) {
                $this->setTimeCookies();
                return $this->refresh();
            }
        }
        
        if ($model_phone->load(Yii::$app->request->post()) && $model_phone->validate()) {
            // Если данные успешно провалидированы, то устанавливаем куку времени на смену пароля
            if ($model_phone->checkPhone()) {
                $this->setTimeCookies();
                return $this->refresh();
            }
        }
        
        if ($user->load(Yii::$app->request->post()) && $user->validate()) {
            $file = UploadedFile::getInstance($user, 'user_photo');
            if (!$user->uploadPhoto($file)) {
                Yii::$app->session->setFlash('error', ['message' => 'Ошибка оновления профиля. Обновите страницу и повторите действие заново']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('success', ['message' => 'Настройки профиля были успешно обновлены']);
            return $this->refresh();
        }

        return $this->render('settings', [
            'user_info' => $user_info,
            'user' => $user,
            'model_password' => $model_password,
            'sms_model' => $sms_model,
            'model_phone' => $model_phone,
            'is_change_password' => $is_change_password,
            'is_change_phone' => $is_change_phone,
        ]);
    }
    
    /*
     * AJAX валидация формы отправки подтверждения СМС кода
     */
    public function actionValidateSmsForm() {
        
        $user_info = $this->permisionUser();
        $user = $user_info->_model;
        
        $sms_model = new SMSForm($user);
        
        if (Yii::$app->request->isAjax && $sms_model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($sms_model);
        }        
    }
    
    /*
     * Отправка СМС кода
     */
    public function actionSendSmsForm($type) {
        
        $user_info = $this->permisionUser();
        $user = $user_info->_model;        
        
        $sms_model = new SMSForm($user);
        
        if ($sms_model->load(Yii::$app->request->post()) && $sms_model->validate()) {
            $sms_model->changeUserInfo($type);
            Yii::$app->session->setFlash('success', ['message' => 'Настройки профиля были успешно обновлены']);
            return $this->redirect(['profile/settings']);
        }
        Yii::$app->session->setFlash('error', ['message' => 'Ошибка сохранения настроек профиля. Обновите страницу и повторите действие заново']);
        return $this->redirect(['profile/settings']);
        
    }
    
    /*
     * Установка времени куки для СМС операций
     */
    private function setTimeCookies() {
        
        $cookies = Yii::$app->response->cookies;
        $name_modal = '_time';
      
        // Количество минут для хранения куки
        $minutes_to_add = 10;

        $cookies->add(new \yii\web\Cookie([
            'name' => $name_modal,
            'value' => '',
            'expire' => strtotime("+ {$minutes_to_add} minutes"),
        ]));        
        
    }
    
    /*
     * Получение куки 
     * Если заданной куки не существует, удаляем запись на смену пароля
     */
    private function hasCookieSMS() {
        
        if (!isset($_COOKIE['_time'])) {
            $_record = SmsOperations::deleteOperation(SmsOperations::TYPE_CHANGE_PASSWORD);
            return false;
        } 
        return true;
    }
    
    /* Отмена операций 
     *      смена пароля
     *      номера мобильного телефона
     * @param $value integer Тип СМС запроса TYPE_CHANGE_PASSWORD(1), TYPE_CHANGE_PHONE(2), TYPE_CHANGE_EMAIL(3)
     */
    public function actionCancelSmsCode() {
        
        $value = Yii::$app->request->post('valueData');
        
        // Удаляем запись из БД
        $_record = SmsOperations::deleteOperation($value);
        // Удаляем куку
        Yii::$app->response->cookies->remove('_time');
        return $this->redirect(['profile/settings']);
    }
    
    /*
     * Формирование нового смс кода
     */
    public function actionGenerateNewSmsCode() {
        
        $value = Yii::$app->request->post('valueData');
        
        $record_sms = SmsOperations::findByTypeOperation($value);
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($record_sms->generateNewSMSCode()) {
                return ['success' => true];
            }
            return ['success' => false];
        }
        return ['success' => false];

    }
    
    
}
