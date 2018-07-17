<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Controller;
    use yii\web\Response;
    use app\models\Services;
    use app\models\CategoryServices;
    use app\models\PaidServices;

/**
 * Description of PaidServicesController
 *
 * @author Ekaterina
 */
class PaidServicesController extends Controller {
    
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
    
    public function actionIndex() {
        return $this->render('index');
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
