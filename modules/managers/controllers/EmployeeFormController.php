<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\web\NotFoundHttpException;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\form\EmployeeForm;
    use app\models\Departments;
    use app\modules\managers\models\User;
    use app\models\Employees;
    use app\modules\managers\models\Posts;
    use app\modules\managers\models\form\ChangePasswordAdministrator;
    use app\modules\managers\models\permission\PermissionsList;
    use app\modules\managers\models\permission\SetPermissions;
    

/**
 * Единый контролер для обработки формы добавления нового сотрудника (пользователя) в систему
 */
class EmployeeFormController extends AppManagersController {
    
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index', 
                            'employee-profile', 
                            'change-password'
                        ],
                        'allow' => true,
                        'roles' => ['EmployeesEdit']
                    ],
                ],
            ],
        ];
    }
    
    /*
     * Главная, форма добавления нового сотрудника
     * 
     * @param $new_employee string Роль пользователя (Администратора, Диспетчер, Специалист)
     */
    public function actionIndex($new_employee) {
        
        // Загружаем модель для создания нового Сотрудника
        $model = new EmployeeForm();
        
        // Формируем список Подразделений
        $department_list = Departments::getArrayDepartments();
        // Должности
        $post_list = [];
        
        if ($new_employee == 'administrator') {
            // Формируем список для натсройки прав доступа
            $permissions_list = PermissionsList::getPermissionList($for_admnin = true);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $permission_list_post = Yii::$app->request->post()['permission_list'];
            
            $file = UploadedFile::getInstance($model, 'photo');
            $model->photo = $file;
            
            $employee_id = $model->addEmployer($file, $new_employee, $permission_list_post);
            
            if ($employee_id != null) {
                return $this->redirect(['employee-profile', 
                    'type' => $new_employee,
                    'employee_id' => $employee_id]);
            }
            Yii::$app->session->setFlash('error', ['message' => 'Во время создания профиля сотрудника произошла ошибка. Обновите страницу и повторите действие заново.']);
        }
        
        
        return $this->render('index', [
            'role' => $new_employee,
            'model' => $model,
            'department_list' => $department_list,
            'post_list' => $post_list,
            'permissions_list' => isset($permissions_list) ? $permissions_list : null,
        ]);
    }
    
    /*
     * Редактирование профиля сотрудника
     */
    public function actionEmployeeProfile($type, $employee_id) {
        
        if ($type == null || $employee_id == null) {
            throw new NotFoundHttpException(404);
        }

        // Получаем информацию о сотруднике
        $employee_info = Employees::findByID($employee_id);
        // Получаем информацию о пользователе
        $user_info = User::findByEmployeeId($employee_id);
        
        // Проверяем существование заданного сотрудника
        if ($employee_info === null && $user_info === null) {
            throw new NotFoundHttpException(404);
        }
        
        // Список Отделений
        $department_list = Departments::getArrayDepartments();
        // Список должностей
        $post_list = Posts::getPostList($employee_info->employee_department_id);
        // Список ролей
        $role = User::getRole($type);
        
        // Список прав пользователя (для checkBoxList)
        if ($type == 'administrator') {
            // Формируем список для настройки прав доступа
            $permissions_list = PermissionsList::getPermissionList($for_admnin = true);
            // Получаем массив текущих прав пользователя
            $current_permissions = PermissionsList::getUserPermission($user_info->id);
        } elseif ($type == 'dispatcher') {
            // Проверяем права на добаление Новостей у Диспетчера
            $current_permissions = PermissionsList::canAddNews($user_info->id);
        }
        
        // Сохраняем данные с формы
        if ($user_info->load(Yii::$app->request->post()) && $employee_info->load(Yii::$app->request->post())) {
            
            $is_valid = $user_info->validate();
            $is_valid = $employee_info->validate() && $is_valid;
            
            if ($is_valid) {
                $file = UploadedFile::getInstance($user_info, 'user_photo');
                $user_info->uploadPhoto($file);
                $employee_info->save();
                
                if ($type == 'administrator') {
                    // Получаем массив разрешений для администратора
                    $permission_list_post = Yii::$app->request->post()['permission_list'];
                    $set_permissions = SetPermissions::changePermissions($user_info->id, $role = $type, $permission_list_post);
                } elseif ($type == 'dispatcher') {
                    // Получаем массив разрешений для Диспетчера
                    $permission_list_post = Yii::$app->request->post()['User']['is_new'];
                    $set_permissions = SetPermissions::changePermissions($user_info->id, $role = $type, $permission_list_post);
                }
                
            } else {
                Yii::$app->session->setFlash('error', ['message' => 'Во время обновления профиля произошла ошибка. Обновите страницу и повторите действие заново.']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('success', ['message' => "Профиль сотрудника {$employee_info->fullName} успешно обновлен."]);
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        
        return $this->render('employee-profile', [
            'type' => $type,
            'employee_info' => $employee_info,
            'user_info' => $user_info,
            'department_list' => $department_list,
            'post_list' => $post_list,
            'role' => $role,
            'permissions_list' => isset($permissions_list) ? $permissions_list : null,
            'current_permissions' => $current_permissions ? $current_permissions : null,
        ]);
        
    }
    
    /*
     * Смена пароля пользователя администратором
     */
    public function actionChangePassword($user_id) {
        
        $user = User::findByID($user_id);
        $model = new ChangePasswordAdministrator($user);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->changePassword()) {
                Yii::$app->session->setFlash('success', ['message' => "Пароль пользователя {$user->user_login} был успешно изменен."]);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('error', ['message' => 'Во время смены пароля произошла ошибка. Обновите страницу и повторите действие заново.']);
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    
}
