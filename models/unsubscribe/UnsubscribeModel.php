<?php

    namespace app\models\unsubscribe;
    use yii\base\Model;
    use app\models\User;

/**
 * Отмена рассылки
 */
class UnsubscribeModel extends Model {
    
    public $_user;

    public function __construct(User $user, $cofig = []) {
        
        $this->_user = $user;
        parent::__construct($cofig);
        
    }
    
    public function unsubscribe() {
        
        $this->_user->user_check_email = false;
        return $this->_user->save() ? true : false;
        
    }
    
}
