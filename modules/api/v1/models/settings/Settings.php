<?php

    namespace app\modules\api\v1\models\settings;
    use Yii;
    use yii\base\Model;
    use app\models\User;
    use app\models\TokenPushMobile;
    

/**
 * Настройки приложения
 */
class Settings extends Model {
    
    public $_user;
    public $_push;


    public function __construct(User $user, $token = null) {
        $this->_user = $user;
        $this->_push = TokenPushMobile::findOne(['token' => $token]);
        parent::__construct();
    }

    /*
     * Получить статусы включения push, email уведомления в мобильном приложении
     */
    public function getSettings() {
        return [
            'push' => $this->_user->user_check_email ? true : false,
            'email' => ($this->_push && $this->_push->status) ? true : false,
        ];
    }
    
    
    
}
