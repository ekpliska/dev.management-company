<?php

    use app\rbac;
    use yii\rbac\Item;
    use yii\rbac\Rule;

class AdministratorRule extends Rule {
    
    public $name = 'administrator';
    
    public function execute($user, $item, $params) {
        
        //
        
    }
    
}
