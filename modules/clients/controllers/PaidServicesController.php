<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Response;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\Services;
    use app\models\CategoryServices;
    use app\models\PaidServices;
    use app\modules\clients\models\_searchForm\searchInPaidServices;

/**
 * Платные заявки
 */
class PaidServicesController extends AppClientsController {
    
    
    /*
     * Страница "Заказать услугу"
     */
    public function actionIndex() {
        
        // Загружаем модель добавления новой заявки
        $new_order = new PaidServices([
            'scenario' => PaidServices::SCENARIO_ADD_SERVICE,
        ]);
        
        // получаем список всех платных услуг
        $name_services_array = CategoryServices::getCategoryNameArray();
        // Получаем список услуг по первой категории
        $pay_services = Services::getPayServices(key($name_services_array));
        
        return $this->render('index', [
            'new_order' => $new_order, 
            'pay_services' => $pay_services, 
            'name_services_array' => $name_services_array
        ]);
        
    }
    
    /*
     * Страница "История услуг"
     * @param ActiveQuery Все платные услуги для текущего пользователя
     * @param intereg $accoint_id Значение ID лицевого счета из глобального dropDownList (хеддер)
     */    
    public function actionOrderServices() {
        
        $this->permisionUser();
        $account_id = $this->_current_account_id;
        
        $all_orders = PaidServices::getOrderByUser($account_id);
        
        return $this->render('order-services', ['all_orders' => $all_orders]);
        
    }
    
    /*
     * Поиск заявок по исполнителю
     */
    public function actionSearchBySpecialist() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $value = Yii::$app->request->post('searchValue');
        $account_id = $this->_current_account_id;
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            // Загружаем модель поиска
            $model = new searchInPaidServices();
            
            $all_orders = $model->search($value, $account_id);
            
            $data = $this->renderAjax('data/grid', ['all_orders' => $all_orders]);
            
            return ['status' => true, 'data' => $data];
            
        }
        
        return ['status' => false];
        
    }

    /*
     * Фильтр услуг по категориям
     */
    public function actionFilterCategoryServices() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $category = Yii::$app->request->post('categoryId');
        if (!is_numeric($category)) {
            return ['success' => false];
        }
        
        if (Yii::$app->request->isPost) {
            $pay_services = Services::getPayServices($category);
            $data = $this->renderPartial('data/service-lists', [
                'pay_services' => $pay_services, 
            ]);            
            return ['success' => true, 'is' => true, 'data' => $data];
        }
        return ['success' => false];
    }
    
    /*
     * Создание заявки на платную услугу
     */
    public function actionCreatePaidRequest($category, $service) {
        
        $account_id = $this->_current_account_id;
        
        // Загружаем модель добавления новой заявки
        $new_order = new PaidServices([
            'scenario' => PaidServices::SCENARIO_ADD_SERVICE,
        ]);
        
        $categoty_name = CategoryServices::getNameCategory($category);
        $service_name = Services::getServicesName($service);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('form/add-paid-request', [
                'new_order' => $new_order,
                'category' => $categoty_name,
                'service' => $service_name,
            ]);
        }
        
        if ($new_order->load(Yii::$app->request->post())) {
            $record = $new_order->addOrder($account_id);
//            var_dump($new_order->errors); die();
            if ($record) {
                Yii::$app->session->setFlash('success', ['message' => "Заявка ID{$record} была успешно создана"]);
                return $this->redirect(['paid-services/order-services']);
            } else {
                Yii::$app->session->setFlash('error', ['message' => 'Ошибка создания заявки. Обновите страницу и повторите действие заново']);
                return $this->redirect(['index']);                
            }
        }
        
    }
    
    /*
     * Добавление оценки на заявку
     */
    public function actionAddScoreRequest() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $score = Yii::$app->request->post('score');
        $request_id = Yii::$app->request->post('request_id');
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            
            // Ищем заявку на платную услугу
            $result = PaidServices::findOne(['services_id' => $request_id]);
            // Если заявка найдена и оценка поставлена
            if ($result && $result->addGrade($score)) {
                return ['success' => true];
            }
            
            return ['success' => false];
        }
        
        return ['success' => false];
        
    }
        
}
