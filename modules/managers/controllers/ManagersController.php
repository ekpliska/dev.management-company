<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;
    

/**
 * Description of ManagersController
 *
 * @author Ekaterina
 */
class ManagersController extends AppManagersController {
    
    public function actionIndex() {
        
        $user_info = $this->permisionUser();
        $user_model = $user_info->_model;
        
        $employer_info = \app\models\Employers::find()->andWhere(['employers_id' => $user_info->employerID])->one();
        
        
        return $this->render('index', [
            'user_info' => $user_info,
            'user_model' => $user_model,
            'employer_info' => $employer_info,
        ]);
    }

}
