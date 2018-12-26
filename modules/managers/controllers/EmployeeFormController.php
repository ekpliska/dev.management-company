<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\form\EmployeeForm;
    use app\models\Departments;
    use app\modules\managers\models\User;
    use app\models\Employees;
    use app\modules\managers\models\Posts;

/**
 * Единый контролер для обработки формы добавления нового сотрудника (пользователя) в систему
 */
class EmployeeFormController extends AppManagersController {
    
    /*
     * Главная, форма добавления нового сотрудника
     * 
     * @param $new_employee string Роль пользователя (Администратора, Диспетчер, Специалист)
     */
    public function actionIndex($new_employee) {
        
        $model = new EmployeeForm();
        
        $department_list = Departments::getArrayDepartments();
        $post_list = [];
        $roles = User::getRoles();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $file = UploadedFile::getInstance($model, 'photo');
            $model->photo = $file;
            $employee_id = $model->addDispatcher($file, $new_employee);
            if ($employee_id) {
                return $this->redirect(['employee-profile', 
                    'type' => $new_employee,
                    'employee_id' => $employee_id]);
            }
            Yii::$app->session->setFlash('error', ['message' => 'Во время создания профиля сотрудника произошла ошибка. Обновите страницу и повторите действие заново']);
        }
        
        
        return $this->render('index', [
            'role' => $new_employee,
            'model' => $model,
            'department_list' => $department_list,
            'post_list' => $post_list,
            'roles' => $roles,
        ]);
    }
    
    /*
     * Редактирование профиля сотрудника
     */
    public function actionEmployeeProfile($type, $employee_id) {
        
        if ($type == null || $employee_id == null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }

        $employee_info = Employees::findByID($employee_id);
        $user_info = User::findByEmployeeId($employee_id);
        
        if ($employee_info === null && $user_info === null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $department_list = Departments::getArrayDepartments();
        $post_list = Posts::getPostList($employee_info->employee_department_id);
        
        $role = User::getRole($type);
        
        
        if ($user_info->load(Yii::$app->request->post()) && $employee_info->load(Yii::$app->request->post())) {
            
            $is_valid = $user_info->validate();
            $is_valid = $employee_info->validate() && $is_valid;
            
            if ($is_valid) {
                $file = UploadedFile::getInstance($user_info, 'user_photo');
                $user_info->uploadPhoto($file);
                $employee_info->save();
            } else {
                Yii::$app->session->setFlash('error', ['message' => 'Во время обновления профиля произошла ошибка. Обновите страницу и повторите действие заново']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('success', ['message' => 'Профиль сотрудника успешно обновлем']);
            return $this->redirect(Yii::$app->request->referrer);
        }        
        
        
        return $this->render('employee-profile', [
            'type' => $type,
            'employee_info' => $employee_info,
            'user_info' => $user_info,
            'department_list' => $department_list,
            'post_list' => $post_list,
            'role' => $role,
        ]);
        
    }
    
}
