<?php

    namespace app\modules\clients\controllers;
    use app\modules\clients\controllers\AppClientsController;
    

/**
 * Default controller for the `clients` module
 */
class ClientsController extends AppClientsController
{
    
    /**
     * Главная страница
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
}
