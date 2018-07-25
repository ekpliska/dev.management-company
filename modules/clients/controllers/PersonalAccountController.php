<?php
    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Controller;
    use yii\data\ActiveDataProvider;
    use app\models\PersonalAccount;
    use app\modules\clients\models\AddPersonalAccount;
    use app\models\User;
    use app\modules\clients\models\FilterForm;
    use app\models\Rents;
    use app\models\Organizations;
    use app\models\AccountToUsers;
    use app\modules\clients\models\ClientsRentForm;
    use app\models\Houses;

/**
 * Контроллер по работе с разделом "Лицевой счет"
 */
class PersonalAccountController extends Controller {
    

    public function actionIndex($user, $username) {
        
        $user_info = User::findByUser($user, $username);
        
        if ($user_info === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        // Загружаем форму для добавления лицевого счета
        $add_account = new AddPersonalAccount();
        
        // Загружаем в провайдер данных информацию об основном лицевом счете
        $dataProvider = new ActiveDataProvider([
            'query' => PersonalAccount::findByClientID($user_info->user_client_id),
            'pagination' => false,
        ]);
        
        // Форма для фильтрации лицевых счетов
        $_filter = new FilterForm();
        
        // Получить список всех лицевых счетов пользователя        
        $account_all = PersonalAccount::findByClient($user_info->user_client_id);
        
        // Получить список всех арендаторов собственника со статусом "Не активен"
        $all_rent = Rents::findByClientID($user_info->user_client_id);
        
        // Получить список всех домов закрепленных за собственником
        $all_house = Houses::findByClientID($user_info->user_client_id);
        
        return $this->render('index', [
            'user_info' => $user_info,
            'add_account' => $add_account, 
            'account_all' => $account_all,
            '_filter' => $_filter,
            'dataProvider' => $dataProvider,
            'all_rent' => $all_rent,
            'all_house' => $all_house,
        ]);
        
    }

    /*
     * Метод для фильтра лицевых счетов
     */
    public function actionList($id) {
        if (isset($_POST['id'])) {
            return $this->refresh();
        } else {
            if (Yii::$app->request->isAjax) {
                $account_number = PersonalAccount::findOne(['account_id' => $id]);
                return $this->renderAjax('list', ['model' => $account_number]);
            }
        }
        
//        if (isset($_POST['id']))
//        {
//            return $this->render('index');
//        }else{
//            $searchModel = new LocationSearch();
//            $model = new Location();
//            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//            return $this->render('list',[
//                'model' => $model,
//                'searchModel' => $searchModel,
//                'dataProvider' => $dataProvider,
//            ]);
//        }
    }
    
    public function actionValidateRecord() {
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model = new AddPersonalAccount();
            $model->load(Yii::$app->request->post());
            return \yii\bootstrap\ActiveForm::validate($model);
        }
        throw new \yii\web\BadRequestHttpException('Не верный формат запроса!');   
    }
    
    
    public function actionValidateAddRentForm() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            $model = new ClientsRentForm([
                'scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION,
            ]);

            if ($model->load(Yii::$app->request->post())) {
                
                if ($model->validate()) {
                    return ['status' => true];
                }
            }
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
                var_dump($account_form->getFirstErrors());die;
                Yii::$app->session->setFlash('form', ['success' => false, 'error' => 'При отправке формы возникла ошибка, попробуйте заполнить форму заново']);
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
                    Yii::$app->session->setFlash('form', ['success' => false, 'error' => 'При отправке формы возникла ошибка, попробуйте заполнить форму заново (*) ']);
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
     
}
