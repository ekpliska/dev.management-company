<?php
    
    namespace app\modules\clients\components;
    use yii\web\Controller;
    use yii\base\Behavior;
    use Yii;
    use app\models\PersonalAccount;
    
/*
 * 
 */
class checkPersonalAccount extends Behavior {
    
    /*
     * @param array $_list Массив всех лицевых счетов пользователя
     */
    public $_list;
    
    /*
     * @param int $_choosing ID выбранного лицевого счета
     * в dropDownList
     */
    public $_choosing;

    public function events() {
        return [
            Controller::EVENT_AFTER_ACTION => 'getListAccount',
            Controller::EVENT_BEFORE_ACTION => 'sessionAccound',
        ];
    }
    
    /*
     * Получить список всех лицевых счетов пользователя
     * @param array $_list Массив, содержит список всех лицевых счетов пользователя
     */
    public function getListAccount() {
        return $this->_list = PersonalAccount::getListAccountByUserID(Yii::$app->user->identity->id);
    }
    
    /*
     * Метод чтения cookie и session выбранного лицевого счета
     * Если кука пустая, то берем значение из сессии
     * В противном случае для куки и сесси устанавливаем параметром первый ID лицевого счета из списка dorpDownList
     */
    public function sessionAccound() {
        
        $session = Yii::$app->session;
        $cookies = Yii::$app->response->cookies;
        $choosing = '';
        
        // Получить первый ID лицевого счета из списка dropDownList
        $first_account = key($this->getListAccount(Yii::$app->user->identity->id));
        
        if (!$session->isActive) {
            $session->open();
        }
        
        /*
         * Проверяем наличие выбранного лицевого счета в куке
         * Если кука есть, то получаем из нее значение лицевого счета
         */
        if (Yii::$app->request->cookies->has('choosingAccount')) {
            $choosing = Yii::$app->request->cookies->get('choosingAccount')->value;
        } else 
            if ($session->has('choosingAccount')) {
                $choosing = $session->get('choosingAccount');
            } else {
                $session->set('choosingAccount', $first_account);    
                $cookies->add(new \yii\web\Cookie ([
                    'name' => 'choosingAccount',
                    'value' => $first_account,
                    'expire' => time() + 60*60*24*7,
                ]));
            }
        
        return $this->_choosing = $choosing;
        
    }
}
