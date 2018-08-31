<?php

    namespace app\modules\clients\widgets;
    use yii\base\InvalidConfigException;
    use yii\base\Widget;

/**
 * Description of UserInfo
 *
 * @author Ekaterina
 */
class UserInfo extends Widget {
    
    public $_user;
    public $_choosing;
    public $_list;

    public function init() {
        
        if ($this->_choosing == null && $this->_list == null && $this->_user == null) {
            throw new InvalidConfigException('Ошибка передачи параметров');
        }
        
        parent::init();        
        
    }

    public function run() {
        
        return $this->render('userinfo/profile', [
            'user_info' => $this->_user, 
            'choosing' => $this->_choosing, 
            'list' => $this->_list]);
        
    }
    
}
