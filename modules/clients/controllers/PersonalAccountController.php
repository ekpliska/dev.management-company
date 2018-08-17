<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\base\InvalidConfigException;
    use yii\web\Response;
    use yii\data\ActiveDataProvider;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\PersonalAccount;
    use app\modules\clients\models\AddPersonalAccount;
    use app\models\User;
    use app\models\Rents;
    use app\models\Organizations;
    use app\models\AccountToUsers;
    use app\modules\clients\models\ClientsRentForm;
    use app\models\Houses;
    use app\models\Counters;

/**
 * Контроллер по работе с разделом "Лицевой счет"
 */
class PersonalAccountController extends AppClientsController {
    
    /*
     * Главная страница
     * @param array $account_info Информация по лицевому счету Собственника
     * @param array $account_all Список всех лицевых счетов Собственника
     */
    public function actionIndex() {
        
        $user_info = $this->permisionUser();
        
        // Получаем информация по лицевому счету Собственника
        $account_info = PersonalAccount::findByClientProfile($user_info->user_client_id);
        
        // Получить список всех лицевых счетов пользователя        
        $account_all = PersonalAccount::findByClient($user_info->user_client_id);
        
        return $this->render('index', [
            'user_info' => $user_info,
            'account_info' => $account_info,
            'account_all' => $account_all,
        ]);
        
    }
    
    /*
     * Страница "Квитанции ЖКУ"
     * receipts of housing and public utilities (receipts-of-hapu)
     */
    public function actionReceiptsOfHapu() {
        
        $user_info = $this->permisionUser();
        
        // Получить список всех лицевых счетов пользователя        
        $account_all = PersonalAccount::findByClient($user_info->user_client_id);
        
        return $this->render('receipts-of-hapu', [
            'account_all' => $account_all,
        ]);
    }
    
    /*
     * Страница "Платеж"
     */
    public function actionPayment() {
        
        return $this->render('payment');
        
    }

    /*
     * Страница "Приборы учета"
     */
    public function actionCounters() {

        $user_info = $this->permisionUser();

        // Получаем текущую дату
        $current_date = time();        
        
        // Получаем номер текущего месяца
        $current_month = date('n');
        
        // Получить список всех лицевых счетов пользователя        
        $account_all = PersonalAccount::findByClient($user_info->user_client_id);
        
        $account_info = PersonalAccount::findByClientProfile($user_info->user_client_id);
        
        $counters = new ActiveDataProvider([
            'query' => Counters::getReadingCurrent($account_info->account_id, $current_month),
        ]);
        
        return $this->render('counters', [
            'current_date' => $current_date,
            'account_all' => $account_all,
            'counters' => $counters,
        ]);
        
    }
    
    /*
     * Метод фильтра лицевых счетов
     * dropDownList
     */
    public function actionList($account) {
        
        if (empty($account)) {
            throw new InvalidConfigException('При передаче параметров произошла ошибка');
        }
        
        if (Yii::$app->request->isGet && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $account_info = PersonalAccount::findOne(['account_id' => $account]);
            // return ['success' => true, 'message' => $account_info];
            $data = $this->renderAjax('_data-filter/list', ['model' => $account_info]);
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];

    }
    
    /*
     * Валидация формы "Добавление нового арендатора"
     */
    public function actionValidateAddRentForm() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        // Если данные пришли через пост и аякс
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            // Объявляем модель арендатор, задаем сценарий валидации для формы
            $model = new ClientsRentForm([
                'scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION,
            ]);
            
            // Если модель загружена
            if ($model->load(Yii::$app->request->post())) {
                // и прошла валидацию
                if ($model->validate()) {
                    // Для Ajax запроса возвращаем стутас, ок
                    return ['status' => true];
                }
            }
            // Инваче, запросу отдаем ответ о поваленной валидации и ошибки
            return [
                'status' => false,
                'errors' => $model->errors,
            ];
        }
        return ['status' => false];
    }
    
    
    /*
     * Вызов формы добавления нового лицевого счета
     */
    public function actionShowAddForm() {
        
        $all_organizations = Organizations::getOrganizations();
        $user_info = AccountToUsers::findByUserID(Yii::$app->user->identity->user_id);
        
        // Получить список всех арендаторов собственника со статусом "Не активен"
        $all_rent = Rents::findByClientID($user_info->personalAccount->client->clients_id);

        // Получить список жилой прощади, принадлежание собственнику
        $all_flat = Houses::findByClientID($user_info->personalAccount->client->clients_id);

        
        // Форма Лицевой счет
        $add_account = new AddPersonalAccount();
        // Форма добавить Арендатора
        $add_rent = new ClientsRentForm();
        
        return $this->render('_form/_add_account', [
            'all_organizations' => $all_organizations,
            'user_info' => $user_info,
            'all_rent' => $all_rent,
            'all_flat' => $all_flat,
            'add_account' => $add_account,
            'add_rent' => $add_rent,
        ]);
    }
    
    /*
     * Добавление нового лицевого счета
     */
    public function actionAddRecordAccount($form) {
        
        // Получить ID пользователя
        $user_client_id = AccountToUsers::find()
                ->andWhere(['user_id' => Yii::$app->user->identity->user_id])
                ->with(['user'])
                ->one();

        if (Yii::$app->request->isPost && $user_client_id->user->user_client_id) {
            
            $account_form = new AddPersonalAccount();
            $account_form->load(Yii::$app->request->post());
            $account_form->validate();
            
            $new_account = $account_form->account_number;
            
            if ($account_form->hasErrors()) {
                
                Yii::$app->session->setFlash('form', ['success' => false, 'error' => 'При отправке формы возникла ошибка, попробуйте заполнить форму заново (*) ']);
                if (Yii::$app->request->referrer) {
                    Yii::$app->response->setStatusCode(400);
                    return Yii::$app->request->isAjax ? \yii\helpers\Json::encode(['success' => false]) : $this->redirect(Yii::$app->request->referrer);
                }
                return Yii::$app->request->isAjax ? \yii\helpers\Json::encode(['success' => false, 'error' => 'Ошибка формы 1']) : $this->goHome();
            }
            
            // Заполняем массив данными о новом аренадтор
            $data_rent = Yii::$app->request->post('ClientsRentForm');
            // Проверям массив на пустоту
            if (array_filter($data_rent)) {
                // Если массив не пустой, передаем в модель Арендатор данные
                $rent_form = new ClientsRentForm($data_rent);
                
                // Выводим ошибки в случае неудачной валидации
                if (!$rent_form->validate()) {
                    Yii::$app->session->setFlash('form', ['success' => false, 'error' => 'При отправке формы возникла ошибка, попробуйте заполнить форму заново (**) ']);
                    if (Yii::$app->request->referrer) {
                        Yii::$app->response->setStatusCode(400);
                        return Yii::$app->request->isAjax ? \yii\helpers\Json::encode(['success' => false]) : $this->redirect(Yii::$app->request->referrer);                        
                    }
                    return Yii::$app->request->isAjax ? \yii\helpers\Json::encode(['success' => false]) : $this->goHome();
                    // return $rent_form->errors;
                }
                
                // Если данные прошли валидацию и успешно сохранены
                $_rent = $rent_form->saveRentToUser($data_rent, $new_account);
                if ($_rent) {
                    // Сохраняем новый лицевой счет
                    $account_form->saveAccountChekRent($_rent);
                    Yii::$app->session->setFlash('form', ['success' => true, 'message' => 'Лицевой счет создан. Созданный лицевой счет связан с новым арендатором']);
                }
                
            } else {
                $account_form->saveAccountChekRent($_rent = null);
                Yii::$app->session->setFlash('form', ['success' => true, 'message' => 'Лицевой счет создан']);
            }
            
            return $this->redirect(Yii::$app->request->referrer);            
        }
        return $this->goHome();
        
    }
    
    /*
     * Метод, проверяет существование пользователя по текущему ID (user->identity->user_id)
     * Пользователь имеет доступ только к странице своего профиля
     * В противном случае выводим исключение
     */
    public function permisionUser() {
        
        $_user_id = Yii::$app->user->identity->user_id;
        $user_info = User::findByUser($_user_id);
        
        if ($user_info) {
            return $user_info;
        } else {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }        
    }
    
     
}
