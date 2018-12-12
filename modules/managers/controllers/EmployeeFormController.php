<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;

/**
 * Единый контролер для обработки формы добавления нового сотрудника (пользователя) в систему
 */
class EmployeeFormController extends AppManagersController {
    
    /*
     * Главная, форма добавления нового сотрудника
     */
    public function actionIndex($new_employee) {
        
        return $this->render('index', [
            'role' => $new_employee,
        ]);
    }
    
}
