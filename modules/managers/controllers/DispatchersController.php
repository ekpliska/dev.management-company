<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\Dispatchers;
    use app\modules\managers\models\form\EmployerForm;
    use app\models\Employers;
    use app\models\Departments;
    use app\modules\managers\models\User;

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
        
        $model = new EmployerForm();
        
        $department_list = Departments::getArrayDepartments();
        $post_list = [];
        $roles = User::getRole();
        
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->addDispatcher();
            return $this->redirect('index');
        }
        
        return $this->render('add-dispatcher', [
            'model' => $model,
            'department_list' => $department_list,
            'post_list' => $post_list,
            'roles' => $roles,
        ]);
    }
}
