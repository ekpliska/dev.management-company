<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\form\EmployerForm;
    use app\models\Departments;
    use app\modules\managers\models\User;
    use app\modules\managers\models\Dispatchers;

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
        
        $dispatchers = new \yii\data\ActiveDataProvider([
            'query' => Dispatchers::getListDispatchers(),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 30,
            ]            
        ]);
        
        return $this->render('index', [
            'dispatchers' => $dispatchers,
        ]);
    }
    
    /*
     * Создать нового диспетчера
     * 
     * @param model $model Модель Новый сотрудник
     * @param array $department_list Списко подраздерений
     * @param array $roles Роли пользователя
     */
    public function actionAddDispatcher() {
        
        $this->view->title = 'Диспетчер (+)';
        
        $model = new EmployerForm();
        
        $department_list = Departments::getArrayDepartments();
        $post_list = [];
        $roles = User::getRole();
        
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $file = UploadedFile::getInstance($model, 'photo');
            $model->photo = $file;
            $model->addDispatcher($file);
            return $this->redirect('edit-profile');
        }
        
        return $this->render('add-dispatcher', [
            'model' => $model,
            'department_list' => $department_list,
            'post_list' => $post_list,
            'roles' => $roles,
        ]);
    }
    
}
