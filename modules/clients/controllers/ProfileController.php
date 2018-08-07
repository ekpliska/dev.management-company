<?php

    namespace app\modules\clients\controllers;
    use yii\web\Controller;
    use Yii;
    use yii\web\UploadedFile;
    use yii\web\NotFoundHttpException;
    use app\models\User;
    use app\modules\clients\models\ClientsRentForm;
    use app\models\Clients;
    use app\models\Rents;
    use app\models\PersonalAccount;
    
    

/**
 * Default controller for the `clients` module
 */
class ProfileController extends Controller
{
    
    public $_is_rent = false;


    /**
     * Главная страница
     */
    public function actionIndex() {
        
        $user_info = $this->permisionUser();
        $client = Clients::findOne(['clients_id' => $user_info->user_client_id]);
        
        // Получаем все активные лицевые счета Собственника (для dropDownList)
        $accounts_list = PersonalAccount::findByClient($user_info->user_client_id);

        // Получаем все лицевые счета Собственника не связанны с арендаторами (для dropDownList)
        $accounts_list_rent = PersonalAccount::findByClientForBind($user_info->user_client_id);
        
        // Получаем ифнормацию по лицевому счету Собственника
        $accounts_info = PersonalAccount::findByClientProfile($user_info->user_client_id); 
        
        /* Статус наличия у собственника арендатора
         * Если имеется Арендатор, то загружаем данные Арендатор для формы
         */
        $is_rent = false;
        if (Rents::isActiveRents($client->id)) {
            $is_rent = true;
            $model_rent = Rents::findOne(['rents_id' => $accounts_info->personal_rent_id]);
        } else {
            $is_rent = false;
            $model_rent = null;
        }
        
        // Форма добавить Арендатора
        $add_rent = new ClientsRentForm(['scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION]);
        
        return $this->render('index', [
            'user' => $user_info,
            'accounts_list' => $accounts_list,
            'accounts_list_rent' => $accounts_list_rent,
            'is_rent' => $is_rent,
            'accounts_info' => $accounts_info,
            'model_rent' => $model_rent,
            'add_rent' => $add_rent,
            'not_active_rents' => Rents::getNotActiveRents($client->id),
        ]);
        
    }
    
    public function actionUpdateProfile($form) {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $user_info = $this->findUser(Yii::$app->user->id);
        
        if (Yii::$app->request->isPost) {
            
            if ($user_info->load(Yii::$app->request->post())) {
                
                try {
                    
                    $file = UploadedFile::getInstance($user_info, 'user_photo');
                    // Сохраняем профиль
                    $user_info->uploadPhoto($file);

                    // Получаем ID выбранного лицевого счета
                    $_account = Yii::$app->request->post('_list-account');
                    // Находим запись выбранного лицевого счета
                    $account_number = PersonalAccount::findByNumber($_account);
                    
                    // Заполняем массив данными нового Арендатора
                    // $data_rent_new = Yii::$app->request->post('ClientsRents');
                    // $rent_form = new ClientsRentForm($data_rent_new);
                    
                    // Получаем данные арендатора
                    $data_rent_old = Yii::$app->request->post('Rents');
                    $rent = Rents::findOne(['rents_id' => $account_number->personal_rent_id]);
                    
                    // Проверяем данные пришедшие из пост для выбранного арендатора
                    if ($data_rent_old && $account_number && $rent->load(Yii::$app->request->post())) {
                        if ($rent->validate()) {
                            $rent->save();
                        }
                    }
                    
//                    if ($data_rent_old && $account_number) {
//                        $rent_form->saveRentToUser($data_rent, $account_number->account_number);
//                    }
                    
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
    
    protected function findUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
    }
    
    /*
     * Фильтр выбора лицевого счета
     */
    public function actionCheckAccount() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax) {
            
            // Из пост рапроса получаем ID лицевого счета и собственника
            $account_id = Yii::$app->request->post('dataAccount');
            $client_id = Yii::$app->request->post('dataClient');
            
            // Ищем арендатора, закрепленного за указанным лицевым счетом
            $model = PersonalAccount::findByRent($account_id, $client_id);
            
            /*
             * Если арендатор существует, генерирурем для него модель
             */
            $model_rent = Rents::findOne(['rents_id' => $model->personal_rent_id]);
            if ($model_rent) {
                $this->_is_rent = true;
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
//            $_account = PersonalAccount::findOne($account);
//            if ($_rent && $_account) {
//                Yii::$app->session->setFlash('error', 'При передаче параметров произошла ошибка. Перезагрухите страницу');
//                return $this->render('index');
//            }
            
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

    
//    /*
//     * Валидация формы "Добавление нового арендатора"
//     */
//    public function actionValidateAddRentForm() {
//        
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        
//        // Если данные пришли через пост и аякс
//        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
//            
//            // Объявляем модель арендатор, задаем сценарий валидации для формы
//            $model = new ClientsRentForm([
//                'scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION,
//            ]);
//            
//            // Если модель загружена
//            if ($model->load(Yii::$app->request->post())) {
//                // и прошла валидацию
//                if ($model->validate()) {
//                    // Для Ajax запроса возвращаем стутас, ок
//                    return ['status' => true];
//                }
//            }
//            // Инваче, запросу отдаем ответ о проваленной валидации и ошибки
//            return [
//                'status' => false,
//                'errors' => $model->errors,
//            ];
//        }
//        return ['status' => false];
//    }
    
//    public function actionAddNewRent() {
//        
//        $_account = Yii::$app->request->post('_list-account');
//        $data_rent = Yii::$app->request->post('ClientsRentForm');
//        
//        $rent_form = new ClientsRentForm($data_rent);
//        
//        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
//            
//            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//            if ($rent_form->load(Yii::$app->request->post())) {
//                if ($rent_form->saveRentToUser($data_rent, '12345678900')) {
//                    return ['success' => $rent_form];
//                }
//            }
//        }
//        
//        return $this->renderAjax('_test');
//        
//    }
    
}
