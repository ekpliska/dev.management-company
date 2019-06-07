<?php

    namespace app\modules\managers\widgets;
    use Yii;
    use yii\base\Widget;
    use app\models\PersonalAccount;

/**
 * Виджет для отслеживания подключения к API "Лицевой счет"
 */
class StatusClientAPI extends Widget {
    
    public $_status = true;

    public function init() {
        
        // Получаем любой номер в БД лицевых счетов
        $account_number = PersonalAccount::find()
                ->limit(1)
                ->asArray()
                ->one();
        
        // Формируем проверочный запрос к API "Лицевой счет"
        $array_request = [
            'Номер лицевого счета' => $account_number['account_number'],
            'Номер месяца' => date('m'),
            'Год' => date('y'),
        ];
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $result = Yii::$app->client_api->getPreviousCounters($data_json);
        
        if ($result == null) {
            $this->_status = false;
        }
        
        parent::init();
    }
    
    public function run() {
        
        return $this->render('statusclientapi/default', [
            'status' => $this->_status,
        ]);
        
    }
    
}
