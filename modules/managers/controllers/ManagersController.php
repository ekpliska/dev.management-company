<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\data\ActiveDataProvider;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Employees;
    use app\modules\managers\models\Managers;
    use app\models\Departments;
    use app\modules\managers\models\Posts;
    use app\models\ChangePasswordForm;
    use app\modules\managers\models\searchForm\searchEmployees;

/**
 * Профиль Админимтратора
 *
 */
class ManagersController extends AppManagersController {
    
    
    /*
     * Главная страница - Администраторы
     */
    public function actionIndex() {
        
        $model = new searchEmployees();
        
        $departments = Departments::getArrayDepartments();
        $posts = Posts::getArrayPosts();
        
        $manager_list = $model->search(Yii::$app->request->queryParams, 'administrator');
        
//        $manager_list = new ActiveDataProvider([
//            'query' => Managers::getListManagers(),
//            'pagination' => [
//                'forcePageParam' => false,
//                'pageSizeParam' => false,
//                'pageSize' => 30,
//            ]
//        ]);
        
        return $this->render('index', [
            'model' => $model,
            'manager_list' => $manager_list,
            'departments' => $departments,
            'posts' => $posts,
        ]);
        
    }
    
    /*
     * Страница Профиль Администратора
     * 
     * @param array $user_info Информация о текущем пользователе
     * @param object $user_model Модель профиля пользователя
     * @param array $employee_info Информация о сотруднике
     * @param array $department_list Список подразделений
     * @param array $post_lis Списко должностей
     */
    public function actionViewProfile() {
        
        $user_info = $this->permisionUser();
        $user_model = $user_info->_model;
        $user_model->scenario = 'edit administration profile';
        
        $employee_info = Employees::find()->andWhere(['employee_id' => $user_info->employeeID])->one();
        
        $department_list = Departments::getArrayDepartments();
        $post_list = Posts::getPostList($employee_info->employee_department_id);
        
        if ($user_model->load(Yii::$app->request->post()) && $employer_info->load(Yii::$app->request->post())) {
            
            $is_valid = $user_model->validate();
            $is_valid = $employee_info->validate() && $is_valid;
            
            if ($is_valid) {
                $file = UploadedFile::getInstance($user_model, 'user_photo');
                $user_model->uploadPhoto($file);
                $employee_info->save();
            } else {
                Yii::$app->session->setFlash('profile-admin-error');
            }
            Yii::$app->session->setFlash('profile-admin');
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return $this->render('view-profile', [
            'user_info' => $user_info,
            'user_model' => $user_model,
            'employee_info' => $employee_info,
            'department_list' => $department_list,
            'post_list' => $post_list,
        ]);
    }
    
    public function actionSettingsProfile() {
        
        $user_info = $this->permisionUser();
        $user_model = $user_info->_model;
        
        // Загружаем модель смены пароля
        $model_password = new ChangePasswordForm($user_model);
        
        if ($model_password->load(Yii::$app->request->post())) {
            if ($model_password->changePassword()) {
                Yii::$app->session->setFlash('profile-admin');
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('profile-admin-error');
            }
        }
        
        return $this->render('settings-profile', [
            'model_password' => $model_password,
        ]);
        
    }

}