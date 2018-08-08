<?php

    namespace app\modules\clients\controllers;
    use yii\web\Controller;
    

/**
 * Default controller for the `clients` module
 */
class ClientsController extends Controller
{
    /**
     * Главная страница
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
}
