<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Houses;

/**
 * Жилищный фонд
 */
class EstatesController extends AppManagersController {
    
    public function actionIndex() {
        
        $houses_list = Houses::getAllHouses();
        
        return $this->render('index', [
            'houses_list' => $houses_list,
        ]);
        
    }
    
}
