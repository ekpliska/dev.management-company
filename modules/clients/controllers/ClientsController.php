<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use app\models\SiteSettings;
    use app\models\PersonalAccount;
    

/**
 * Собственник, Новостная лента
 */
class ClientsController extends AppClientsController
{
    
    /**
     * Главная страница
     * Формирование списка новостей для собственника
     */
    public function actionIndex($block = 'important_information') {

        // Получаем ID текущего лицевого счета
        $account_id = $this->_current_account_id;
        // Получаем массив содержащий ID ЖК, ID дома, ID квартиры, номер подъезда
        $living_space = Yii::$app->userProfile->getLivingSpace($account_id);
        
        // Ифонмация из настроек для Промоблока
        $promo_info = SiteSettings::findOne(['id' => 1]);
        
        return $this->render('index', [
            'living_space' => $living_space,
            'indications' => $this->getCountersIndication(),
            'payments' => $this->getPaymentsList(),
            'promo_text' => $promo_info->promo_block,
        ]);
        
    }
    
    /*
     * Получить теукщие показания приборов учета
     * по текущему лицевому счету
     * Для вкладки "Приборы учета", Профиль пользователя
     */
    private function getCountersIndication() {
        
        $account_number = $this->_current_account_number;
        
        // Формируем запрос для текущего расчетного перирода
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Номер месяца' => date('m'),
            'Год' => date('Y'),
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $indications = Yii::$app->client_api->getPreviousCounters($data_json);
        
        return $indications;
        
    }
    
    /*
     * Получить список оплаченных/не оплаченных квитанций
     */
    public function getPaymentsList() {
        
        // Получить номер текущего лицевого счета
        $account_number = $this->_current_account_number;
        // Получаем номер текущего месяца и год
        $current_period = date('Y-n');
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Период начало' => null,
            'Период конец' => $current_period,
        ];
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $receipts_lists = Yii::$app->client_api->getReceipts($data_json);
        
        // Проверяем актуальность баланса по лицевому счету
        $summ_last = $receipts_lists ? $receipts_lists[0]['Сумма к оплате'] : null;
        if ($summ_last) {
            $this->checkBalance($summ_last);
        }
        
        return $receipts_lists;
    }
    
    /*
     * Проверка баланса Лицевого счета
     */
    private function checkBalance($last_sum) {
        
        $square = Yii::$app->userProfile->getLivingSpace($this->_current_account_id)['flat']['flats_square'];
        $old_balance = Yii::$app->userProfile->balance;

        $data_array = [
            "Номер лицевого счета" => "{$this->_current_account_number}",
            "Сумма последней квитанции" => "{$last_sum}",
            "Площадь жилого помещения" => "{$square}",
        ];
        // Преобразуем массив в json
        $data_json = json_encode($data_array, JSON_UNESCAPED_UNICODE);
        // Отправляем запрос по API        
        $result_api = Yii::$app->client_api->accountRegister($data_json);
        
        // Если данные по API получены, то проверяем текущий баланс с новым
        if ($result_api['Лицевой счет']['success']) {
            $new_balance = $result_api['Лицевой счет']['Баланс'];
            if ($old_balance != $new_balance) {
                $account = PersonalAccount::findOne($this->_current_account_id);
                $account->changeBalance($new_balance);
                return true;
            }
            
        }
        return true;
        

    }
    
    /*
     * Отправка показаний приборов учета
     */
    public function actionSendIndications() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $indications = Yii::$app->request->post('dataForm');
        
        $array = [];
        
        foreach ($indications as $key => $indication) {
            $_array = [
                "ID" => $key,
                "Дата снятия показания" => date('Y-m'),
                "Текущее показание" => $indication,
            ];
            $array[] = $_array;
            
        }
        
        $array_request['Приборы учета'] = $array;
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $result = Yii::$app->client_api->setCurrentIndications($data_json);
            
        if (Yii::$app->request->isAjax) {
            if (!$result) {
                return ['success' => false];
            }
            return ['success' => true];
        }
        
    }
    
}
