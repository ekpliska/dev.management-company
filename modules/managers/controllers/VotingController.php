<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\form\VotingForm;

/**
 *  Голосование
 */
class VotingController extends AppManagersController {
    
    /*
     * Голосование, главная страница
     */
    public function actionIndex() {
        
        return $this->render('index');
        
    }
    
}
