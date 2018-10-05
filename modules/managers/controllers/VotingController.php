<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;

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
