<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use app\modules\dispatchers\controllers\AppDispatchersController;

/**
 * Профиль собственника
 */
class ClientProfileController extends AppDispatchersController {
    
    public function actionIndex() {
        return $this->render('index');
    }
    
}
