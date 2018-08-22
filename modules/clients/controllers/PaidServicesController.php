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
     */
    public function actionIndex() {
        
        $this->permisionUser();
        
        $all_orders = new ActiveDataProvider([
            'query' => PaidServices::find(Yii::$app->user->identity->user_id)->orderBy(['created_at' => SORT_DESC])
        ]);
        
        return $this->render('index', ['all_orders' => $all_orders]);
    }
    
    /*
     * Страница "Заказать слугу"
     */
    public function actionOrderServices() {
        
        // Модель создания новой заявки
        $new_order = new PaidServices([
            'scenario' => PaidServices::SCENARIO_ADD_SERVICE,
        ]);
        
        // Получаем список все платных заявок
        $pay_services = Services::getPayServices();
        
        // получаем список всех плтаных завок по категориям
        $categorys = CategoryServices::getAllCategory();

        return $this->render('order-services', ['categorys' => $categorys, 'new_order' => $new_order, 'pay_services' => $pay_services]);
        
    }
    
    /*
     * Метод сохранения заявки
     */
    public function actionAddRecord() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;

        $new_order = new PaidServices([
            'scenario' => PaidServices::SCENARIO_ADD_SERVICE,
        ]);
        /*
         * Если данные с формы добавления новой заявки загружены,
         * то вызываем метод сохранения данных
         */
        if (Yii::$app->request->isAjax) {
            if ($new_order->load(Yii::$app->request->post())) {
                if ($new_order->validate()) {
                    Yii::$app->session->setFlash('success', 'Ваша заявка создана');
                    $new_order->addOrder();
                    return $this->asJson(['status' => true]);
                }
            }
            Yii::$app->session->setFlash('success', 'При создании заявки возникла ошика');
            return $this->asJson([
                'status' => false,
                'errors' => $new_order->errors,
            ]);
        }        

        return $this->asJson(['status' => false]);
    }
    
}
