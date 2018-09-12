<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Employers;
    use app\models\Departments;
    use app\modules\managers\models\Posts;
    

/**
 * Профиль Админимтратора
 *
 */
class ManagersController extends AppManagersController {
    
    /*
     * Главная страница - Профиль Администратора
     * 
     * @param array $user_info Информация о текущем пользователе
     * @param object $user_model Модель профиля пользователя
     * @param array $employer_info Информация о сотруднике
     * @param array $gender_list Пол /Мужской, женский/
     * @param array $department_list Список подразделений
     * @param array $post_lis Списко должностей
     */
    public function actionIndex() {
        
        $user_info = $this->permisionUser();
        $user_model = $user_info->_model;
        $user_model->scenario = 'edit administration profile';
        
        $employer_info = Employers::find()->andWhere(['employers_id' => $user_info->employerID])->one();
        
        $gender_list = Employers::getGenderArray();
        $department_list = Departments::getArrayDepartments();
        $post_list = Posts::getPostList($employer_info->employers_department_id);
        
        if ($user_model->load(Yii::$app->request->post()) && $employer_info->load(Yii::$app->request->post())) {
            
            $is_valid = $user_model->validate();
            $is_valid = $employer_info->validate() && $is_valid;
            
            if ($is_valid) {
                $file = UploadedFile::getInstance($user_model, 'user_photo');
                $user_model->uploadPhoto($file);
                $employer_info->save();
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        return $this->render('index', [
            'user_info' => $user_info,
            'user_model' => $user_model,
            'employer_info' => $employer_info,
            'gender_list' => $gender_list,
            'department_list' => $department_list,
            'post_list' => $post_list,
        ]);
    }
    
    public function actionShowPost($departmentId) {
        
        $department_list = Departments::find()
                ->andWhere(['departments_id' => $departmentId])
                ->asArray()
                ->count();
        $post_list = Posts::find()
                ->andWhere(['posts_department_id' => $departmentId])
                ->asArray()
                ->all();
        
        if ($department_list > 0) {
            foreach ($post_list as $post) {
                echo '<option value="' . $post['posts_id'] . '">' . $post['posts_name'] . '</option>';
            }
        } else {
            echo '<option>-</option>';
        }
        
    }

}