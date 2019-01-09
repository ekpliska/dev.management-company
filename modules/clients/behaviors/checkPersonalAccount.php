<?php
    
    namespace app\modules\clients\behaviors;
    use yii\web\Controller;
    use yii\base\Behavior;
    use Yii;
    use app\models\PersonalAccount;
    
/*
 * Поведение формирует список лицевых счетов текушего пользователя
 * после авторизации
 */
class checkPersonalAccount extends Behavior {
    
    /*
     * @param array $_list Массив всех лицевых счетов пользователя
     */
    public $_lists;
    
    /*
     * @param array $_current_account_id ID текущего лицевого счета
     */
    public $_current_account_id;
    
    /*
     * @param array $_current_account_number Нормер текущего лицевого счета
     */
    public $_current_account_number;


    public function events() {
        return [
            Controller::EVENT_BEFORE_ACTION => 'accountInfo',
        ];
    }
    
    /*
     * Получить список всех лицевых счетов пользователя
     * Получить ID текущего лицевого счета
     * Получить Номер текущего лицевого счета
     */
    public function getPersonalAccountList() {
        
        $result = PersonalAccount::getListAccountByUserID(Yii::$app->user->identity->id);
        $this->_lists = $result['lists'];
        $this->_current_account_id = $result['current_account']['id'];
        $this->_current_account_number = $result['current_account']['number'];
        
        return [
            'lists' => $this->_lists,
            'current_id' => $this->_current_account_id,
            'current_number' => $this->_current_account_number,
        ];
    }
    
    public function accountInfo($event) {
        return $this->getPersonalAccountList();
    }
    
    
//    /*
//     * Метод чтения cookie выбранного лицевого счета
//     * Если кука пустая, то берем значение из сессии
//     * В противном случае для куки и сесси устанавливаем параметром первый ID лицевого счета из списка dorpDownList
//     */
//    public function sessionAccount() {
//        
//        $cookies = Yii::$app->response->cookies;
//        $choosing = '';
//        
//        // Получаем список лицевых счетов пользователя
//        $array_account = $this->getListAccount(Yii::$app->user->identity->id);
//        
//        // Получить первый ID лицевого счета из списка dropDownList
//        $first_account = key($array_account);
//        
//        /*
//         * Проверяем наличие выбранного лицевого счета в куке
//         * Если кука есть, то получаем из нее значение лицевого счета
//         */
//        if (Yii::$app->request->cookies->has('_userAccount')) {
//            $choosing = Yii::$app->request->cookies->get('_userAccount')->value;
//        } else {
//            $cookies->add(new \yii\web\Cookie ([
//                'name' => '_userAccount',
//                'value' => $first_account,
//                'expire' => time() + 60*60*24*7,
//            ]));
//        }
//        
//        // Получаем ID лицевого счета
//        $this->_choosing = $choosing;
//        // Получаем номер лицевого счета
//        $this->_value_choosing = $choosing ? $array_account[$choosing] : $array_account[$first_account];
//        
//        
//        return [
//            '_choosing' => $this->_choosing,
//            '_value_choosing' => $this->_value_choosing,
//        ];
//        
//    }
}
