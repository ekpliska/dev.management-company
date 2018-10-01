<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;

/**
 * Новости
 */
class NewsController extends AppManagersController {
    
    public function actionIndex() {
        return $this->render('index');
    }
    
}
