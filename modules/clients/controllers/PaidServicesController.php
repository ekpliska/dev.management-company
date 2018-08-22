<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Response;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\Services;
    use app\models\CategoryServices;
    use app\models\PaidServices;

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
        
        $this->permisionUser();
        $accoint_id = $this->_choosing;
        
        $all_orders = new ActiveDataProvider([
            'query' => PaidServices::getOrderByUder($accoint_id)
        ]);
        
        return $this->render('index', ['all_orders' => $all_orders]);
    }
    
    /*
     * Страница "Заказать слугу"
     */
    public function actionOrderServices() {
        
        $accoint_id = $this->_choosing;
        
        // Модель создания новой заявки
        $new_order = new PaidServices([
            'scenario' => PaidServices::SCENARIO_ADD_SERVICE,
        ]);
        
        if ($new_order->load(Yii::$app->request->post())) {
            if ($new_order->addOrder($accoint_id)) {
                return $this->refresh();
            }
        }
        
        // Получаем список все платных заявок
        $pay_services = Services::getPayServices();
        
        // получаем список всех плтаных завок по категориям
        $categorys = CategoryServices::getAllCategory();

        return $this->render('order-services', ['categorys' => $categorys, 'new_order' => $new_order, 'pay_services' => $pay_services]);
        
    }
    
//    /*
//     * Метод сохранения заявки
//     */
//    public function actionAddRecord() {
//        
//        $accoint_id = $this->_choosing;
//        
//        Yii::$app->response->format = Response::FORMAT_JSON;
//
//        $new_order = new PaidServices([
//            'scenario' => PaidServices::SCENARIO_ADD_SERVICE,
//        ]);
//        /*
//         * Если данные с формы добавления новой заявки загружены,
//         * то вызываем метод сохранения данных
//         */
//        if (Yii::$app->request->isAjax) {
//            if ($new_order->load(Yii::$app->request->post())) {
//                if ($new_order->validate()) {
//                    Yii::$app->session->setFlash('success', 'Ваша заявка создана');
//                    $new_order->addOrder($accoint_id);
//                    return $this->asJson(['status' => true]);
//                }
//            }
//            Yii::$app->session->setFlash('success', 'При создании заявки возникла ошика');
//            return $this->asJson([
//                'status' => false,
//                'errors' => $new_order->errors,
//            ]);
//        }        
//
//        return $this->asJson(['status' => false]);
//    }
    
}
