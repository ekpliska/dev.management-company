<?php

    namespace app\modules\clients\widgets;
    use yii\base\InvalidConfigException;
    use yii\base\Widget;

/**
 * Профиль пользователя из навигационного меню
 */
class UserInfo extends Widget {
    
    public function init() {
        
        parent::init();        
        
    }

    public function run() {
        
        return $this->render('userinfo/profile');
        
    }
    
}
