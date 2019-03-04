<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Departments;
    use app\modules\managers\models\searchForm\searchEmployees;
    use app\modules\managers\models\Posts;

/**
 * Диспетчеры
 */
class EmployeesController extends AppManagersController {
    
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'dispatchers', 
                            'specialists', 
                            'administrators',
                        ],
                        'allow' => true,
                        'roles' => ['EmployeesView']
                    ],
                    [
                        'actions' => [
                            'query-delete-dispatcher', 
                            'query-delete-specialist',
                        ],
                        'allow' => true,
                        'roles' => ['EmployeesEdit']
                    ],
                ],
            ],
        ];
    }
    
    /*
     * Все Диспетчеры
     */
    public function actionDispatchers() {
        
        $model = new searchEmployees();
        
        $dispatchers = $model->search(Yii::$app->request->queryParams, 'dispatcher');
        
        $departments = Departments::getArrayDepartments();
        $posts = Posts::getArrayPosts();
        
        return $this->render('dispatchers', [
            'model' => $model,
            'dispatchers' => $dispatchers,
            'departments' => $departments,
            'posts' => $posts,
        ]);
    }

    /*
     * Все Специалисты
     */
    public function actionSpecialists() {
        
        $model = new searchEmployees();
        
        $specialists = $model->search(Yii::$app->request->queryParams, 'specialist');
        
        $departments = Departments::getArrayDepartments();
        $posts = Posts::getArrayPosts();
        
        return $this->render('specialists' , [
            'specialists' => $specialists,
            'model' => $model,
            'departments' => $departments,
            'posts' => $posts,
        ]);
    }
    
    /*
     * Все Администраторы
     */
    public function actionAdministrators() {
        
        $model = new searchEmployees();
        
        $departments = Departments::getArrayDepartments();
        $posts = Posts::getArrayPosts();
        
        $manager_list = $model->search(Yii::$app->request->queryParams, 'administrator');
        
        return $this->render('administrators', [
            'model' => $model,
            'manager_list' => $manager_list,
            'departments' => $departments,
            'posts' => $posts,
        ]);
    }
        
}
