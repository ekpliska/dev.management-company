<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\base\Model;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Organizations;
    use app\models\Departments;
    use app\models\Posts;

/**
 * Тарифы
 */
class SettingsController extends AppManagersController {
    
    /*
     * Настройка, главная страница
     * Реквизиты компании
     */
    public function actionIndex() {
        
        $model = Organizations::findOne(['organizations_id' => 1]);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect(['index']);
        }
        
        return $this->render('index', [
            'model' => $model,
        ]);
    }
    
    /*
     * Подразделения, Должности
     */
    public function actionServiceDuty() {
        
        $departments = Departments::find()->indexBy('department_id')->all();
        $posts = Posts::find()->indexBy('post_id')->all();
        $department_lists = Departments::getArrayDepartments();
        
        
        if (Model::loadMultiple($departments, Yii::$app->request->post()) && Model::validateMultiple($departments)) {
            foreach ($departments as $department) {
                $department->save(false);
            }
            return $this->redirect('service-duty');
        }

        if (Model::loadMultiple($posts, Yii::$app->request->post()) && Model::validateMultiple($posts)) {
            foreach ($posts as $post) {
                $post->save();
            }
            return $this->redirect('service-duty');
        }

        return $this->render('service-duty', [
            'departments' => $departments,
            'posts' => $posts,
            'department_lists' => $department_lists,
        ]);
        
    }
    
}
