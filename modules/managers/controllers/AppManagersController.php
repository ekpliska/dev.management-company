<?php

    namespace app\modules\managers\controllers;
    use yii\web\Controller;
    use yii\filters\AccessControl;
    use Yii;
    use app\models\CategoryServices;
    use app\models\Services;
    use app\models\Departments;
    use app\modules\managers\models\Posts;
    use app\modules\managers\models\User;
    use app\modules\managers\models\form\RequestForm;
    use app\models\Employees;
    use app\modules\managers\models\Dispatchers;
    use app\modules\managers\models\Specialists;

/*
 * Общий контроллер модуля Managers
 * Наследуется всеми остальными контроллерами
 */
    
class AppManagersController extends Controller {
    
    /*
     * Назначение прав доступа к модулю "Администратор"
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['administrator'],
                    ],
                ],
            ],
        ];
    }
    
    public function permisionUser() {
        return Yii::$app->userProfileCompany;
    }
    
    /*
     * Метод получения cookies
     */
    public function actionReadCookies($cookie_name) {

        /*
         * Проверяем наличие выбранного дома из списка Жилых комплексов
         * Если кука есть, то получаем из нее значение номера выбранного дома
         */
        $name_cookie = "{$cookie_name}";
        if (Yii::$app->request->cookies->has($name_cookie)) {
            return Yii::$app->request->cookies->get($name_cookie)->value;
        }
        
        
//        switch ($cookie_name) {
//            case 'choosingHouse':
//                if (Yii::$app->request->cookies->has('choosingHouse')) {
//                    return Yii::$app->request->cookies->get('choosingHouse')->value;
//                }
//                break;
//            case 'choosingCategory':
//                if (Yii::$app->request->cookies->has('choosingCategory')) {
//                    return Yii::$app->request->cookies->get('choosingCategory')->value;
//                }
//                break;
//            case 'choosingRequest':
//                if (Yii::$app->request->cookies->has('choosingRequest')) {
//                    return Yii::$app->request->cookies->get('choosingRequest')->value;
//                }
//                break;
//            default:
//                return false;
//                
//        }
        
    }
    
    /*
     * Формирование зависимых списков 
     * Выбор Должности/Специализации зависит от выбора Подразделения
     */
    public function actionShowPost($departmentId) {
        
        $department_list = Departments::find()
                ->andWhere(['department_id' => $departmentId])
                ->asArray()
                ->count();
        $post_list = Posts::find()
                ->andWhere(['posts_department_id' => $departmentId])
                ->asArray()
                ->all();
        
        if ($department_list > 0) {
            foreach ($post_list as $post) {
                echo '<option value="' . $post['post_id'] . '">' . $post['post_name'] . '</option>';
            }
        } else {
            echo '<option>-</option>';
        }
    }
    
    /*
     * Формирование зависимых списков
     * Выбор названия услуги зависит от ее категории
     */
    public function actionShowNameService($categoryId) {
        
        $category_list = CategoryServices::find()
                ->andWhere(['category_id' => $categoryId])
                ->asArray()
                ->count();
        
        $service_list = Services::find()
                ->andWhere(['services_category_id' => $categoryId])
                ->asArray()
                ->all();
        
        if ($category_list > 0) {
            foreach ($service_list as $service) {
                echo '<option value="' . $service['service_id'] . '">' . $service['services_name'] . '</option>';
            }
        } else {
            echo '<option>-</option>';
        }
        
    }

    
    public function actionShowHouses($phone) {
        
        $model = new RequestForm();
        $client_id = $model->findClientPhone($phone);
        
        $house_list = \app\models\PersonalAccount::find()
                ->select(['account_id', 'houses_gis_adress', 'houses_number', 'flats_number'])
                ->joinWith(['flat', 'flat.house'])
                ->andWhere(['personal_clients_id' => $client_id])
                ->orWhere(['personal_rent_id' => $client_id])
                ->asArray()
                ->all();
        
        if (!empty($client_id)) {
            foreach ($house_list as $house) {
                $full_adress = 
                        $house['houses_gis_adress'] . ', д. ' .
                        $house['houses_number'] . ', кв. ' .
                        $house['flats_number'];
                echo '<option value="' . $house['account_id'] . '">' . $full_adress . '</option>';
            }
        } else {
            echo '<option>Адрес не найден</option>';
        }        
        
    }
    
    /*
     * Блокировать/Разблокировать Пользователя
     * В профиле пользоваетля
     */
    public function actionBlockUserInView() {
                
        $user_id = Yii::$app->request->post('userId');
        $status = Yii::$app->request->post('statusUser');
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $user_info = User::findByID($user_id);
            $user_info->blockInView($user_id, $status);
            return ['status' => $status, 'user_id' => $user_id];
        }
        
        return ['status' => false];
    }
    
    /*
     * Запрос за удаление Сотрудника,
     * Удаление со страницы Пользователя
     */
    public function actionQueryDeleteEmployee() {
        
        $employee_id = Yii::$app->request->post('employerId');
        $role = Yii::$app->request->post('role');

        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            switch ($role) {
                case 'dispatcher':
                    $requests = Dispatchers::findRequestsNotClose($employee_id);
                    $url = 'employees/dispatchers';
                    break;
                case 'specialist':
                    $requests = Specialists::findRequestsNotClose($employee_id);
                    $url = 'employees/specialists';
                    break;
                case 'administrator':
                    $requests = false;
                    $url = 'managers/index';
                    break;
                default:
                    $requests = false;
                    break;
            }
            
            // Имеются не закрытые заявки
            if ($requests) {
                return ['status' => true, 'isClose' => true];
            }
            // Не закрытых заявок нет, сотрудника удаляем
            $employee = Employees::findOne($employee_id);
            if (!$employee->delete()) {
                Yii::$app->session->setFlash('error', ['message' => "При удалении профиля пользователя {$employee->fullName} произошла ошибка. Обновите страницу и повторите действие заново"]);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('success', ['message' => "Профиль сотрудника {$employee->fullName} успешно удален из системы"]);
            return $this->redirect(["{$url}"]);
        }
        return ['status' => false];
        
    }    
    
}
