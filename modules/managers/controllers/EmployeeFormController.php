<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\form\EmployeeForm;
    use app\models\Departments;
    use app\modules\managers\models\User;

/**
 * Единый контролер для обработки формы добавления нового сотрудника (пользователя) в систему
 */
class EmployeeFormController extends AppManagersController {
    
    /*
     * Главная, форма добавления нового сотрудника
     */
    public function actionIndex($new_employee) {
        
        $model = new EmployeeForm();
        
        $department_list = Departments::getArrayDepartments();
        $post_list = [];
        $roles = User::getRoles();
        
//        switch ($new_employee) {
//            case '':
//        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $file = UploadedFile::getInstance($model, 'photo');
            $model->photo = $file;
            $employer_id = $model->addDispatcher($file, $new_employee);
            if ($employer_id) {
                return $this->redirect(['edit-specialist', 'specialist_id' => $employer_id]);
            }
        }
        
        
        return $this->render('index', [
            'role' => $new_employee,
            'model' => $model,
            'department_list' => $department_list,
            'post_list' => $post_list,
            'roles' => $roles,
        ]);
    }
    
}
