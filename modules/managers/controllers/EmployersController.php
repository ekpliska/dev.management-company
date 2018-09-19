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
class EmployersController extends AppManagersController {
    
    /*
     * Главная страница
     * 
     * Все Лиспетчеры
     */
    public function actionDispatchers() {
        
        $dispatchers = new \yii\data\ActiveDataProvider([
            'query' => Dispatchers::getListDispatchers(),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 30,
            ]            
        ]);
        
        $search_model = new searchEmployer();
        
        return $this->render('dispatchers', [
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
            $employer_id = $model->addDispatcher($file);
            if ($employer_id) {
                return $this->redirect(['edit-dispatcher', 'dispatcher_id' => $employer_id]);
            }
        }
        
        return $this->render('add-dispatcher', [
            'model' => $model,
            'department_list' => $department_list,
            'post_list' => $post_list,
            'roles' => $roles,
        ]);
    }
    
    /*
     * Редактирование профиля Диспетчера
     */
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
    
    /*
     * Сквозной поиск по таблице Диспетчеры
     */
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
    
    /*
     * Запрос за удаление Диспетчера
     */
    public function actionQueryDeleteDispatcher() {
        
        $emploeyr_id = Yii::$app->request->post('employerId');
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            // Проверяем наличие не закрытых заявко
            $requests = Dispatchers::findRequestsNotClose($emploeyr_id);
            // Имеются не закрытые заявки
            if ($requests) {
                return ['status' => true, 'isClose' => true];
            }
            // Не закрытых заявок нет, сотрудника удаляем
            $employer = Employers::findOne($emploeyr_id);
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
}
