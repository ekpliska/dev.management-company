<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\Dispatchers;
    use app\modules\managers\models\form\DispatcherForm;
    use app\models\Employers;
    use app\models\Departments;

/**
 * Диспетчеры
 */
class DispatchersController extends AppManagersController {
    
    /*
     * Главная страница
     * 
     * Все Лиспетчеры
     */
    public function actionIndex() {
        return $this->render('index');
    }
    
    /*
     * Создать нового диспетчера
     */
    public function actionAddDispatcher() {
        
        $model = new DispatcherForm();
        $gender_list = Employers::getGenderArray();
        $department_list = Departments::getArrayDepartments();
        $post_list = [];
        
        return $this->render('add-dispatcher', [
            'model' => $model,
            'gender_list' => $gender_list,
            'department_list' => $department_list,
            'post_list' => $post_list,
        ]);
    }
}
