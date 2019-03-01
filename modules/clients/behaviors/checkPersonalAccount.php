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
    
}
