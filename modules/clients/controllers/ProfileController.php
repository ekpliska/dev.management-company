<?php

    namespace app\modules\clients\controllers;
    use Yii;
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
        
        if (Yii::$app->user->can('clients')) {
            return $this->client($user_info);
        } else {
            return $this->rent($user_info);
        }
        
    }
    
    /*
     * Метод обновления профиля пользователя
     */
    public function actionUpdateProfile() {

        if (Yii::$app->request->isPost) {
            // Если пришел из пост статуса о существующем Арендаторе
            if (isset($_POST['is_rent'])) {
                // Загружаем модель пользователя
                $user_info = $this->permisionUser()->_model;
                $data_rent = Yii::$app->request->post('Rents');
                $rent_info = Rents::find()->where(['rents_id' => $data_rent['rents_id']])->one();
                
                if ($user_info->load(Yii::$app->request->post()) && $rent_info->load(Yii::$app->request->post())) {
                    
                    $is_valid = $user_info->validate();
                    $is_valid = $rent_info->validate() && $is_valid;

                    
                    if ($is_valid) {
                        // Сохраняем профиль Собственника
                        $file = UploadedFile::getInstance($user_info, 'user_photo');
                        $user_info->uploadPhoto($file);
                        
                        // Сохраняем профиль Арендатора
                        $rent_info->save();

                        Yii::$app->session->setFlash('success', ['message' => 'Профиль успешно обновлем']);
                        return $this->redirect(Yii::$app->request->referrer);
                        
                    }
                    Yii::$app->session->setFlash('success', ['error' => 'При обновлении профиль произошла ошибка. Обновите страницу и повторите действие заново']);
                    return $this->redirect(Yii::$app->request->referrer);                    
                }
            } else {
                // иначе сохраняем только профиль пользователя
                $user_info = $this->permisionUser()->_model;
                if ($user_info->load(Yii::$app->request->post()) && $user_info->validate()) {
                    $file = UploadedFile::getInstance($user_info, 'user_photo');
                    $user_info->uploadPhoto($file);
                    Yii::$app->session->setFlash('success', ['message' => 'Профиль успешно обновлем']);
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
        }
        
        Yii::$app->session->setFlash('success', ['error' => 'При обновлении профиль произошла ошибка. Обновите страницу и повторите действие заново']);
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    /*
     * Фильтр выбора лицевого счета
     * dropDownList Лицевой счет
     */
    public function actionCheckAccount() {

        // Из пост запроса получаем ID лицевого счета и собственника
        $account_id = Yii::$app->request->post('dataAccount');
        $client_id = Yii::$app->userProfile->clientID;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax) {
            // Записываем выбранный лицевой счет в куку как текущий
            $this->setChoosingAccountCookie($account_id);
            // Ищем арендатора, закрепленного за указанным лицевым счетом
            $model = PersonalAccount::findByRent($account_id, $client_id);
            
            // Если арендатор существует, генерирурем для него модель
            if (!empty($model->personal_rent_id)) {
                $model_rent = Rents::findOne(['rents_id' => $model->personal_rent_id]);
                if ($model_rent) {
                    $this->_is_rent = true;
                }
            } else {
                $model_rent = [];
            }
            
            $data = $this->renderPartial('_form/rent-view', [
                'form' => ActiveForm::begin(),
                'model_rent' => $model_rent, 
            ]);
           
            return ['status' => true, 'data' => $data, 'is_rent' => $this->_is_rent];
            
        }
        return ['status' => false];
        
    }
    
    /*
     * Проверка наличия арендатора у лицевого счета
     */
    public function actionCheckIsRent($account) {
        
        $client_id = Yii::$app->userProfile->clientID;
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;            
            $is_rent = PersonalAccount::findByRent($account, $client_id);
            $is_rent = $is_rent ? true : false;
            return ['success' => true, 'is_rent' => $is_rent];
        }
        return ['success' => false];
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
     * Редактирование данных арендатора
     * Форма редактирования данных Арендатора
     */
    public function saveRentInfo($data_rent) {
        
        if ($data_rent == null) {
            return Yii::$app->session->setFlash('profile-error');            
        }
        
        $_rent = Rents::find()->andWhere(['rents_id' => $data_rent['rents_id']])->one();
        
        if ($_rent->load(Yii::$app->request->post()) && $_rent->validate()) {
            $_rent->save();
            
            return Yii::$app->session->setFlash('profile');
        }
        
        return Yii::$app->session->setFlash('profile-error');
    }
    
    /*
     * Раздел - Настройки профиля
     * @param array $model_password Модель смены пароля учетной записи
     */
    public function actionSettingsProfile() {
        
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
            $user->save();
            return $this->refresh();
        }

        return $this->render('settings-profile', [
            'user_info' => $user_info,
            'user' => $user,
            'model_password' => $model_password,
            'sms_model' => $sms_model,
            'model_phone' => $model_phone,
            'is_change_password' => $is_change_password,
            'is_change_phone' => $is_change_phone,
        ]);
    }
    
    public function actionValidateSmsForm() {
        
        $user_info = $this->permisionUser();
        $user = $user_info->_model;
        
        $sms_model = new SMSForm($user);
        
        if (Yii::$app->request->isAjax && $sms_model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($sms_model);
        }        
    }
    
    public function actionSendSmsForm($type) {
        
        $user_info = $this->permisionUser();
        $user = $user_info->_model;        
        
        $sms_model = new SMSForm($user);
        
        if ($sms_model->load(Yii::$app->request->post()) && $sms_model->validate()) {
            $sms_model->changeUserInfo($type);
            return $this->redirect(['profile/settings-profile']);
        }        
        
    }
    
    /*
     * Метод формирования вывода Профиля Собственника
     * 
     * @param array $user_info Информация текущем пользователе (Пользователь + Собственник)
     * @param model $_user['user'] Модель Пользователь
     * @param integer $accoint_id Значение ID лицевого счета из глобального dropDownList (хеддер)
     * @param array $accounts_list Получить список всех лицевых счетов закрепленны за Собственником
     * @param array $accounts_list_rent Получить все лицевые счета не связанные с Арендатором
     */
    protected function client($user_info) {
        
        $accoint_id = $this->_choosing;
        $accounts_list = $this->_list;
        
        $model = $user_info->_model;
        $model->scenario = User::SCENARIO_EDIT_PROFILE;
        
        // Получить информацию по текущему лицевому счету
        $accounts_info = PersonalAccount::findByAccountID($accoint_id);
        
        /* Если у текущего лицевого счета есть арендатор, передаем в глабальный параметр _is_rent значение true;
         * Если у текущего лицевого счета арендатора нет, то формируем модель на добавление нового Арендатора
         */
        if (!empty($accounts_info->personal_rent_id)) {
            $this->_is_rent = true;
            $model_rent = Rents::findOne(['rents_id' => $accounts_info->personal_rent_id]);
            
            return $this->render('index', [
                'user' => $model,
                'user_info' => $user_info,
                'accounts_list' => $accounts_list,
                'is_rent' => $this->_is_rent,
                'accounts_info' => $accounts_info,
                'model_rent' => $model_rent,
            ]);
        } else {
            $this->_is_rent = false;
            $model_rent = null;
            $add_rent = new ClientsRentForm(['scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION]);
        
            return $this->render('index', [
                'user' => $model,
                'user_info' => $user_info,
                'accounts_list' => $accounts_list,
                'is_rent' => $this->_is_rent,
                'accounts_info' => $accounts_info,
                'add_rent' => $add_rent,
            ]);
        }
        
    }
    
    /*
     * Метод формирования вывода Профиля Арендатора
     */
    protected function rent($user_info) {
        
        $model = $user_info->_model;
        $accounts_list = $this->_list;
        
        return $this->render('index', [
            'user' => $model,
            'user_info' => $user_info,
            'accounts_list' => $accounts_list,
        ]);
        
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
        
        $account = $this->_choosing;
        
        if ($client == null || $account == null) {
            return 'Ошибка отправки формы';
        }
        
        $model = new ClientsRentForm(['scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION]);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->saveRentToUser($client, $account);
            return $this->redirect(['profile/index']);
        }
        
    } 
    
    /*
     * Перезапись в куку номер текущего лицевого счета
     */
    private function setChoosingAccountCookie($account_id) {
        
        Yii::$app->session->set('_userAccount', $account_id);
        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => '_userAccount',
            'value' => $account_id,
            'expire' => time() + 60*60*24*7,
        ]));        
        
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
        return $this->redirect(['profile/settings-profile']);
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
