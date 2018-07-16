<?php

    namespace app\modules\clients\controllers;
    use yii\web\Controller;
    use app\models\Services;
    use app\models\CategoryServices;
    use app\models\PaidServices;

/**
 * Description of PaidServicesController
 *
 * @author Ekaterina
 */
class PaidServicesController extends Controller {
    
    public function actionOrderServices() {
        
        $new_order = new PaidServices([
            'scenario' => PaidServices::SCENARIO_ADD_SERVICE,
        ]);
        
        $categorys = CategoryServices::getAllCategory();
        return $this->render('order-services', ['categorys' => $categorys, 'new_order' => $new_order]);
        
    }
    
    public function actionIndex() {
        return $this->render('index');
    }
    
}
