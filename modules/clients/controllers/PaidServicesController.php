<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Response;
    use yii\helpers\ArrayHelper;
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
     * Страница "История услуг"
     * @param ActiveQuery Все платные услуги для текущего пользователя
     * @param intereg $accoint_id Значение ID лицевого счета из глобального dropDownList (хеддер)
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
     * Страница "Заказать слугу"
     */
    public function actionOrderServices() {
        
        $this->permisionUser();
        $account_id = $this->_current_account_id;
        
        $all_orders = PaidServices::getOrderByUder($account_id);
        
        return $this->render('order-services', ['all_orders' => $all_orders]);
        
    }
    
    /*
     * Поиск заявок по исполнителю
     */
    public function actionSearchBySpecialist() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $value = Yii::$app->request->post('searchValue');
        $account = Yii::$app->request->post('accountId');
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            // Загружаем модель поиска
            $model = new searchInPaidServices();
            
            $all_orders = $model->search($value, $account);
            
            $data = $this->renderAjax('data/grid', ['all_orders' => $all_orders]);
            
            return ['status' => true, 'data' => $data];
            
        }
        
        return ['status' => false];
        
    }
    
    public function actionFilterCategoryServices($category) {
        
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $pay_services = Services::getPayServices($category);
            
            $data = $this->renderPartial('data/service-lists', [
                'pay_services' => $pay_services, 
            ]);            
            return ['success' => true, 'is' => true, 'data' => $data];
        }
        return ['success' => false];
    }
    
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
            if ($record) {
                Yii::$app->session->setFlash('success', ['message' => "Заявка ID{$record} была успешно создана"]);
                return $this->redirect(['paid-services/order-services']);
            } else {
                Yii::$app->session->setFlash('error', ['message' => 'При создании заявки произошла ошибка. Обновите страницу и повторите действие заново']);
                return $this->redirect(['index']);                
            }
        }
        
    }
        
}
