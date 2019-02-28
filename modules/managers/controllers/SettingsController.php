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
    
    /*
     * Удаление выбранного подразделения, должности
     */
    public function actionDeleteRecord($item, $type) {

        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            switch ($type) {
                case 'department':
                    $result = Departments::findOne($item);
                    break;
                case 'post':
                    $result = Posts::findOne($item);
                    break;
            }

            if (!empty($result)) {
                $result->delete();
            }
        }
        
        return $this->redirect('service-duty');
        
    }
    
}
