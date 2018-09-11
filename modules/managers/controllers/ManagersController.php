<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;

/**
 * Description of ManagersController
 *
 * @author Ekaterina
 */
class ManagersController extends AppManagersController {
    
    public function actionIndex() {
        return $this->render('index');
    }

}
