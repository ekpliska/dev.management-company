<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\web\NotFoundHttpException;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\User;
    use app\modules\clients\models\ClientsRentForm;
    use app\models\Rents;
    use app\models\PersonalAccount;
    use app\modules\clients\models\ChangePasswordForm;
    
    

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
    public function actionUpdateProfile($form) {
        
        if (empty($form)) {
            throw new NotFoundHttpException('При сохранении профиля возникла ошибка. Повторите операцию еще раз');
        }
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $user_info = $this->findUser(Yii::$app->user->id);
        
        if (Yii::$app->request->isPost) {
            
            if ($user_info->load(Yii::$app->request->post())) {
                
                try {
                    
                    $file = UploadedFile::getInstance($user_info, 'user_photo');
                    // Сохраняем профиль
                    $user_info->uploadPhoto($file);

                    Yii::$app->session->setFlash('success', 'Профиль обновлен');
                    
                } catch (Exception $ex) {
                    Yii::$app->errorHandler->logException($ex);
                    Yii::$app->session->setFlash('error', $ex->getMessage());
                }
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return $this->goHome();
    }
    
    /*
     * Фильтр выбора лицевого счета
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
                $model_rent = Rents::findOne(['rents_id' => $model->personal_rent_id]);
                if ($model_rent) {
                    $this->_is_rent = true;
                }
            } else {
                $model_rent = [];
            }
            
            // Форма добавить Арендатора
            $add_rent = new ClientsRentForm(['scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION]);
            
            $data = $this->renderPartial('_form/rent-view', [
                'model' => $model, 
                'model_rent' => $model_rent, 
                'add_rent' => $add_rent, 
            ]);
           
            return ['status' => true, 'model' => $model, 'data' => $data, 'is_rent' => $this->_is_rent];
            
        }
        return ['status' => false];
        
    }
    
    /*
     * Получить информацию об арендаторе
     * checkBox "Арендатор"
     */
    public function actionGetRentInfo($rent) {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax) {
            $_rent = Rents::findOne($rent);
            if ($_rent) {
                return ['status' => true, 'rent' => $_rent];                
            }
        }
        return ['status' => false];
    }
    

    /*
     * Методы:
     *      delete Удалить - арендатора
     *      undo Отвязать - арендатора от лицевоого счета
     *      bind Объединить - арендатора с лицвым счетом
     */
    public function actionChangeRentProfile($action, $rent, $account = null) {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isGet) {
            
            $_rent = Rents::findOne($rent);
            
            switch ($action) {
                case 'delete':
                    if ($_rent) {
                        $_rent->delete();
                        return $this->redirect(Yii::$app->request->referrer);
                    } else {
                        Yii::$app->session->setFlash('error', 'Возникла ошибка (запрос удаления арендатора)');
                        return $this->refresh();
                    }
                    break;

                case 'undo': 
                    if ($_rent) {
                        $_rent->undoRentWithAccount($rent, $account);
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                    break;
                
                case 'bind':
                    if ($_rent) {
                        $_rent->bindRentWithAccount($rent, $account);
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                    break;
                
                default:
                    Yii::$app->session->setFlash('error', 'Ошибка удаления арендатора');
                    return $this->render(Yii::$app->request->referrer);
            }
        }
        
        return ['status' => false];
        
    }

    
    /*
     * Валидация формы "Добавление нового арендатора"
     */
    public function actionValidateAddRentForm() {
        
        $model = new ClientsRentForm([
            'scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION
        ]);
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        
    }
    
    /*
     * Метод добавления нового арендатора
     * объединить с указанным лицевым счетом
     * @param array $data_rent Массив данных о новом арендаторе
     * @param integer $account_number Номер лицвого счета из пост
     */
    public function actionAddNewRent() {
        
        $data_rent = Yii::$app->request->post('ClientsRentForm');
        $account_number = $data_rent['account_id'];
        
        // Проверям корректность полученных данных
        if (!$data_rent && is_int($account_number)) {
            return ['success' => false];
        }
        
        $rent_form = new ClientsRentForm($data_rent);
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            if ($rent_form->load(Yii::$app->request->post()) && $rent_form->validate()) {
                
                $rent_form->saveRentToUser($data_rent, $account_number);
                Yii::$app->session->setFlash('success', 'Для лицевого счета №' . $account_number . ' был создан новый арендатор');
                return ['success' => true];
                
            }
            
            Yii::$app->session->setFlash('error', 'Произошла ошибка при создании нового арендатора. Повторите действие еще раз');
            return ['success' => false];
            
        }
        
        Yii::$app->session->setFlash('error', 'Произошла ошибка при создании нового арендатора. Повторите действие еще раз');
        return $this->render(Yii::$app->request->referrer);
        
    }
    
    
    /*
     * Сохранение данных арендатора
     */
    public function actionSaveRentInfo() {
        
        $_rent_post = Yii::$app->request->post('Rents');
        $_rent = Rents::find()->andWhere(['rents_id' => $_rent_post['rents_id']])->one();
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($_rent->load(Yii::$app->request->post()) && $_rent->validate()) {
                $_rent->save();
            }
            return ['status' => true];
        }
        return ['status' => false];
    }
    
    /*
     * Раздел - Настройки профиля
     * @param array $model_password Модель смены пароля учетной записи
     */
    public function actionSettingsProfile() {
        
        $user_info = $this->permisionUser();
        
        $user = $user_info->_model;
        $user->scenario = User::SCENARIO_EDIT_PROFILE;
        
        // Загружаем модель смены пароля
        $model_password = new ChangePasswordForm($user);
        
        if ($model_password->load(Yii::$app->request->post())) {
            if ($model_password->changePassword()) {
                Yii::$app->session->setFlash('success', 'Пароль от учетной записи успешно сохранен');
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error', 'При обновлении настроек профиль произошла ошибка');
            }
        }
        
        if ($user->load(Yii::$app->request->post())) {
            if ($user->updateEmailProfile()) {
                Yii::$app->session->setFlash('success', 'Даные электронной почты / мобильный номер телефона были обновлены');
            } else {
                Yii::$app->session->setFlash('error', 'При обновлении настроек профиля произошла ошибка');
            }
        }
        
        return $this->render('settings-profile', [
            'user_info' => $user_info,
            'user' => $user,
            'model_password' => $model_password,
        ]);
    }
    
    /*
     * Поиск пользователя по ID
     */
    protected function findUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
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
        $model = $user_info->_model;
        $accounts_list = $this->_list;

        // Поучить информацию по текущему лицевому счету
        $accounts_info = PersonalAccount::findByAccountID($accoint_id);
        
        /* Если у текущего лицевого счета есть арендатор, передаем в глабальный параметр _is_rent значение true;
         * Если у текущего лицевого счета арендатора нет, то формируем модель на добавление нового Арендатора
         */
        if (!empty($accounts_info->personal_rent_id)) {
            $this->_is_rent = true;
            $model_rent = Rents::findOne(['rents_id' => $accounts_info->personal_rent_id]);
        } else {
            $this->_is_rent = false;
            $model_rent = null;
        }
        
        $add_rent = new ClientsRentForm(['scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION]);
        
        return $this->render('index', [
            'user' => $model,
            'user_info' => $user_info,
            'accounts_list' => $accounts_list,
            'is_rent' => $this->_is_rent,
            'accounts_info' => $accounts_info,
            'model_rent' => $model_rent,
            'add_rent' => $add_rent,
        ]);
        
    }
    
    /*
     * Метод формирования вывода Профиля Арендатора
     */
    protected function rent($user_info) {
        
        $model = $user_info->_model;
        
        return $this->render('index', [
            'user' => $model,
            'user_info' => $user_info,
        ]);
        
    }
    
}
