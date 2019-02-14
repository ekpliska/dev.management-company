<?php

    namespace app\modules\dispatchers\widgets;
    use yii\base\Widget;

/**
 * Профиль пользоателя с ролью "Диспетчер"
 */
class DispatcherUserInfo extends Widget {
    
    public function init() {
        
        parent::init();        
        
    }

    public function run() {
        
        return $this->render('dispatcheruserinfo/profile');
        
    }
    
}
