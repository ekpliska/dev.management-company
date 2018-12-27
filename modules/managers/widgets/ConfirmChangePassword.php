<?php

    namespace app\modules\managers\widgets;
    use yii\base\InvalidConfigException;
    use yii\base\Widget;
    use app\modules\managers\models\form\ChangePasswordAdministrator;
    use app\models\User;

class ConfirmChangePassword extends Widget {

    public $user_id;
    
    private $_user;

    public function init() {
        
        parent::init();
        
        if ($this->user_id == null) {
            throw new InvalidConfigException('Ошибка при передаче параметров. Не задан ID пользователя');
        }
        
        $this->_user = User::findByID($this->user_id);
        
    }

    public function run() {
        
        $model = new ChangePasswordAdministrator($this->_user);
        
        return $this->render('confirmchangepassword/default', [
            'user' => $this->_user,
            'model' => $model]);
        
        
    }
}
