<?php

    namespace app\modules\managers\controllers;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Employers;
    

/**
 * Description of ManagersController
 *
 * @author Ekaterina
 */
class ManagersController extends AppManagersController {
    
    public function actionIndex() {
        
        $user_info = $this->permisionUser();
        $user_model = $user_info->_model;
        
        $employer_info = Employers::find()->andWhere(['employers_id' => $user_info->employerID])->one();
        
        $gender_list = Employers::getGenderArray();
        
        
        return $this->render('index', [
            'user_info' => $user_info,
            'user_model' => $user_model,
            'employer_info' => $employer_info,
            'gender_list' => $gender_list,
        ]);
    }

}
