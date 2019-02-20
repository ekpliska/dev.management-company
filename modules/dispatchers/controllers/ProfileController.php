<?php

    namespace app\modules\dispatchers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use app\modules\dispatchers\controllers\AppDispatchersController;
    use app\models\Employees;
    

/**
 * Профиль диспетчера
 */
class ProfileController extends AppDispatchersController {
    
    public function actionIndex() {
        
        // Получаем информацию о текущем пользователе
        $user_info = $this->permisionUser()->_model;
        
        // Получаем информацию о сотруднике
        $employee_info = Employees::findByID(Yii::$app->profileDispatcher->employeeID);
        
        if ($user_info->load(Yii::$app->request->post()) && $user_info->validate()) {
            
            $file = UploadedFile::getInstance($user_info, 'user_photo');
            $user_info->uploadPhoto($file);
            
            Yii::$app->session->setFlash('success', ['message' => 'Профиль сотрудника успешно обновлем']);
        }
        
        return $this->render('index', [
            'user_info' => $user_info,
            'employee_info' => $employee_info,
        ]);
                
    }
        
        
    
}
