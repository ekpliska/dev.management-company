<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Employers;
    use app\models\Departments;
    use app\models\Posts;
    

/**
 * Description of ManagersController
 *
 * @author Ekaterina
 */
class ManagersController extends AppManagersController {
    
    public function actionIndex() {
        
        $user_info = $this->permisionUser();
        $user_model = $user_info->_model;
        $user_model->scenario = 'edit user profile';
        
        $employer_info = Employers::find()->andWhere(['employers_id' => $user_info->employerID])->one();
        
        $gender_list = Employers::getGenderArray();
        $department_list = Departments::getArrayDepartments();
        $post_list = Posts::getArrayPosts();
        
        return $this->render('index', [
            'user_info' => $user_info,
            'user_model' => $user_model,
            'employer_info' => $employer_info,
            'gender_list' => $gender_list,
            'department_list' => $department_list,
            'post_list' => $post_list,
        ]);
    }

}
