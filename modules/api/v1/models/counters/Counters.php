<?php

    namespace app\modules\api\v1\models\counters;
    use Yii;
    use yii\base\Model;
    use app\models\PersonalAccount;
    use app\models\CommentsToCounters;
    use app\models\TypeCounters;

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
            ];
            
            $counters_items[] = $counter_item;
        }
        $results[] = [
            'counters' => $counters_items,
            'comments' => !empty($comments_to_counters) ? $comments : null,
        ];
        
        return $results;
        
    }
    
    public function getCounterInfo($id_counter) {
        
        $current_date = date('Y-m-d');
        $counter_info = $this->_counters;
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
        $_status = (strtotime($counter_info[$_key]["Дата следующей поверки"])) > strtotime($current_date) ? false : true;
        
        $counter_info[$_key] += [
            'is_block' => $_status
        ];
        
        return $counter_info;
    }
    
}
