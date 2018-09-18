<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\web\Response;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\form\EmployerForm;
    use app\models\Departments;
    use app\modules\managers\models\User;
    use app\modules\managers\models\Dispatchers;
    use app\modules\managers\models\searchForm\searchEmployer;

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
        
        $search_model = new searchEmployer();
        
        return $this->render('index', [
            'dispatchers' => $dispatchers,
            'search_model' => $search_model,
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
    
    public function actionSearchDispatcher() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        $value = Yii::$app->request->post('searchValue');
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            // Загружаем модель поиска
            $model = new searchEmployer();
            $dispatchers = $model->searshDispatcer($value);
            $data = $this->renderAjax('data/grid', ['dispatchers' => $dispatchers]);
            return ['status' => true, 'data' => $data];
        }
        return ['status' => false];
        
    }
}
