<?php

    namespace app\modules\managers\controllers;
    use yii\web\Controller;
    use yii\filters\AccessControl;
    use Yii;
    use app\models\Departments;
    use app\modules\managers\models\Posts;    

/*
 * Общий контроллер модуля Managers
 * Наследуется всеми остальными контроллерами
 */
    
class AppManagersController extends Controller {
    
    /*
     * Назначение прав доступа к модулю "Администратор"
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['administrator'],
                    ],
                ],
            ],
        ];
    }
    
    public function permisionUser() {
        return Yii::$app->userProfileCompany;
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
