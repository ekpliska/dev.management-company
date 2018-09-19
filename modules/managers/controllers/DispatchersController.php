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
    use app\models\Employers;
    use app\modules\managers\models\Posts;

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
            return $this->redirect('index');
        }
        
        return $this->render('add-dispatcher', [
            'model' => $model,
            'department_list' => $department_list,
            'post_list' => $post_list,
            'roles' => $roles,
        ]);
    }
    
    public function actionEditDispatcher($dispatcher_id) {
        
        $dispatcher_info = Employers::findByID($dispatcher_id);
        $user_info = User::findByEmployerId($dispatcher_id);
        
        if ($dispatcher_info === null && $user_info === null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $department_list = Departments::getArrayDepartments();
        $post_list = Posts::getPostList($dispatcher_info->employers_department_id);
        $roles = User::getRole();
        
        if ($user_info->load(Yii::$app->request->post()) && $dispatcher_info->load(Yii::$app->request->post())) {
            
            $is_valid = $user_info->validate();
            $is_valid = $dispatcher_info->validate() && $is_valid;
            
            if ($is_valid) {
                $file = UploadedFile::getInstance($user_info, 'user_photo');
                $user_info->uploadPhoto($file);
                $dispatcher_info->save();
            } else {
                Yii::$app->session->setFlash('profile-admin-error');
            }
            Yii::$app->session->setFlash('profile-admin');
            return $this->redirect(Yii::$app->request->referrer);
        }

        
        return $this->render('edit-dispatcher', [
            'dispatcher_info' => $dispatcher_info,
            'user_info' => $user_info,
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
