<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;

/**
 * Профиль пользоателя с ролью "Администратор"
 */
class ManagerUserInfo extends Widget {
    
    public function init() {
        
        parent::init();        
        
    }

    public function run() {
        
        return $this->render('manageruserinfo/profile');
        
    }
    
}
