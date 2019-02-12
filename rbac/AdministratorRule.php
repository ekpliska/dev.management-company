<?php

    namespace app\rbac;
    use yii\rbac\Rule;

class AdministratorRule extends Rule {
    
    public $name = 'Administrator-Rule';
    
    public function execute($user, $item, $params) {
        
        if ($params['user_id'] == $user && $params['permission'] == $item) {
            return true;
        }
        return false;
        
    }
    
}
