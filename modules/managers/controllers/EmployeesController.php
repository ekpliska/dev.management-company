<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\Response;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Departments;
    use app\modules\managers\models\Dispatchers;
    use app\modules\managers\models\Specialists;
    use app\modules\managers\models\searchForm\searchEmployees;
    use app\models\Employers;
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
        
    /*
     * Запрос за удаление Диспетчера
     */
    public function actionQueryDeleteDispatcher() {
        
        $employer_id = Yii::$app->request->post('employerId');
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            // Проверяем наличие не закрытых заявко
            $requests = Dispatchers::findRequestsNotClose($employer_id);
            // Имеются не закрытые заявки
            if ($requests) {
                return ['status' => true, 'isClose' => true];
            }
            // Не закрытых заявок нет, сотрудника удаляем
            $employer = Employers::findOne($employer_id);
            if (!$employer->delete()) {
                Yii::$app->session->setFlash('delete-employer', [
                    'success' => false, 
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз']);
            }
            Yii::$app->session->setFlash('delete-employer', [
                'success' => true, 
                'message' => 'Сотрудник ' . $employer->fullName . ' и его учетная запись были удалены из системы']);
            
            return $this->redirect('dispatchers');
        }
        return ['status' => false];
        
    }

    /*
     * Запрос за удаление Диспетчера
     */
    public function actionQueryDeleteSpecialist() {
        
        $employer_id = Yii::$app->request->post('employerId');
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            // Проверяем наличие не закрытых заявко
            $requests = Specialists::findRequestsNotClose($employer_id);
            // Имеются не закрытые заявки
            if ($requests) {
                return ['status' => true, 'isClose' => true];
            }
            // Не закрытых заявок нет, сотрудника удаляем
            $employer = Employers::findOne($employer_id);
            if (!$employer->delete()) {
                Yii::$app->session->setFlash('delete-employer', [
                    'success' => false, 
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз']);
            }
            Yii::$app->session->setFlash('delete-employer', [
                'success' => true, 
                'message' => 'Сотрудник ' . $employer->fullName . ' и его учетная запись были удалены из системы']);
            
            return $this->redirect('specialists');
        }
        return ['status' => false];
        
    }
    
}
