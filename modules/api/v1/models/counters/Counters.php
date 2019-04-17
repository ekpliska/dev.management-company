<?php

    namespace app\modules\api\v1\models\counters;
    use Yii;
    use yii\base\Model;
    use app\models\PersonalAccount;
    use app\models\CommentsToCounters;
    use app\models\TypeCounters;
    use app\models\PaidServices;

/**
 * Приборы учета
 */
class Counters extends Model {
    
    // Список приборов учета
    public $_counters;
    // Информация по лицевому счету
    public $_account;

    /*
     * Получаем список приборов учета
     */
    public function __construct(PersonalAccount $account, $month = null, $year = null) {
        
        $_month = $month ? $month : date('m');
        $_year = $year ? $year : date('Y');
        
        // Формируем запрос для текущего расчетного перирода
        $array_request = [
            'Номер лицевого счета' => $account->account_number,
            'Номер месяца' => $_month,
            'Год' => $_year,
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $this->_counters = Yii::$app->client_api->getPreviousCounters($data_json);
        $this->_account = $account;
        
    }
    
    /*
     * Получить список приборов учета по текущему лицевому счету
     */
    public function getCountesList() {
        
        // Комментарий  от Администратора для текущего лицевого счета
        $comments_to_counters = CommentsToCounters::getComments($this->_account->account_id);
        $comments = [
            'title' => $comments_to_counters['comments_title'],
            'text' => $comments_to_counters['comments_text']
        ];

        // Массив результата
        $results = [];
        // Массив приборов учета
        $counters_items = [];
        foreach ($this->_counters as $key => $counter) {
            $counter_item = [
                'id_counter' => $counter['ID'],
                'type_counter' => $counter['Тип прибора учета'],
                '_type' => TypeCounters::getTypeCounterMobile($counter['Тип прибора учета']),
                '_status' => strtotime($counter['Дата следующей поверки']) > time() ? false : true,
            ];
            
            $counters_items[] = $counter_item;
        }
        $results = [
            'counters' => $counters_items,
            'comments' => !empty($comments_to_counters) ? $comments : null,
        ];
        
        return $results;
        
    }
    
    public function getCounterInfo($id_counter) {
        
        $counter_info = $this->_counters;
        if (empty($counter_info)) {
            return ['success' => false];
        }
        
        $current_date = date('Y-m-d');
        $_key = 0;

        foreach ($counter_info as $key => $counter) {
            if ($counter['ID'] != $id_counter) {
                unset($counter_info[$key]);
            } else {
                $_key = $key;
            }
        }
        
        if (empty($counter_info)) {
            return ['success' => false];
        }
        
        /*
         * Устанавливаем статус блокировки ввода показаний, 
         *      false Если дата поверки актуальны
         *      true Если дата поверки истекла
         */
        $_status = (strtotime($counter_info[$_key]["Дата следующей поверки"]) > strtotime($current_date)) ? false : true;
        
        $result = [
            'ID' => $counter_info[$_key]['ID'],
            'Тип прибора учета' => $counter_info[$_key]['Тип прибора учета'],
            'Регистрационный номер прибора учета' => $counter_info[$_key]['Регистрационный номер прибора учета'],
            'Дата следующей поверки' => $counter_info[$_key]['Дата следующей поверки'],
            'Дата снятия показания' => $counter_info[$_key]['Дата снятия показания'],
            'Дата снятия предыдущего показания' => $counter_info[$_key]['Дата снятия предыдущего показания'],
            'Предыдущие показание' => $counter_info[$_key]['Предыдущие показание'],
            'Текущее показание' => $counter_info[$_key]['Текущее показание'],
            'Расход' => $counter_info[$_key]['Расход'],
            'is_block' => $_status
        ];
        
        return $result;
    }
    
    /*
     * Отправка показаний
     */
    public function setIndication($data) {
        
        if (!array_key_exists('counter_id', $data) || !array_key_exists('indication', $data)) {
            return ['message' => 'Данные показаний не корректны'];
        }
        
        // Показания текущих приборов учета
        $counter_info = $this->_counters;
        if (empty($counter_info)) {
            return ['success' => false];
        }
        
        // Ключ массива $counter_info
        $_key = 0;
        foreach ($counter_info as $key => $counter) {
            if ($counter['ID'] != $data['counter_id']) {
                unset($counter_info[$key]);
            } else {
                $_key = $key;
            }
        }
        if (empty($counter_info)) {
            return ['success' => false];
        }
        
        // Текущая дата
        $current_date = date('Y-m-d');
        
        // Если текущее показание не указано, то Новое показание сравниваем с предыдущим
        if (empty($counter_info[$_key]['Текущее показание']) && $data['indication'] > $counter_info[$_key]['Предыдущие показание']) {
            return ['message' => "Ошибка подачи показания, предыдущее показание {$counter_info[$_key]['Предыдущие показание']}"];
        } 
        // Если текущее показание указано, то Новое показание сравниваем с текущим
        elseif ($counter_info[$_key]['Текущее показание'] && $data['indication'] < $counter_info[$_key]['Текущее показание']) {
            return ['message' => "Ошибка подачи показания, предыдущее показание {$counter_info[$_key]['Текущее показание']}"];
        }
        // Если ввод показаний заблокирован
        elseif (strtotime($counter_info[$_key]["Дата следующей поверки"]) < strtotime($current_date)) {
            return ['message' => 'Истек срок поверки прибора учета'];
        }
        
        // Отправляем показания
        $array = [
            "ID" => $data['counter_id'],
            "Дата снятия показания" => date('Y-m'),
            "Текущее показание" => $data['indication'],
        ];
    
        $array_request['Приборы учета'] = [
            $array,
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $result = Yii::$app->client_api->setCurrentIndications($data_json);
        
        return $result ? ['success' => true] : ['success' => false];
        
    }
    
    /*
     * Автоматическая заявка на поверку прибора учета
     */
    public function order($id_counter) {
        
        $counter_info = $this->_counters;
        
        foreach ($counter_info as $key => $counter) {
            if ($counter['ID'] != $id_counter) {
                unset($counter_info[$key]);
            } else {
                $_key = $key;
            }
        }
        
        if (empty($counter_info)) {
            return ['success' => false];
        }
        
        $account_id = $this->_account->account_id;
        $type_request = 'Поверка';
        $counter_type = $counter_info[$_key]['Тип прибора учета'];
        $counter_id = $id_counter;
        
        $create_order = PaidServices::automaticRequest($account_id, $type_request, $counter_type, $counter_id);
        
        return $create_order ? ['success' => true] : ['success' => false];
        
    }
    
}
