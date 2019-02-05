<?php

    namespace app\modules\dispatchers\controllers;
    use yii\web\Controller;

/**
 * Общий контроллер "Диспетчер"
 */
class AppDispatchersController extends Controller
{
    /**
     * Главная страница
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
