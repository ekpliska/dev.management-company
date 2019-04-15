<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Response;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\PaidServices;
    use app\models\CommentsToCounters;

/**
 * Показания приборов учета
 */
class CountersController extends AppClientsController {
    
    public function actionIndex() {
        
        // Статус текущих показаний
        $is_current = true;
        
        $account_id = $this->_current_account_id;
        $account_number = $this->_current_account_number;
        
        // Получаем список зявок сформированных автоматически на поверу приборов учета
        $auto_request = PaidServices::notVerified($account_id);
        
        // Получаем комментарии по приборам учета Собсвенника. Комментарий формирует Администратор системы
        $comments_to_counters = CommentsToCounters::getComments($account_id);

        // Формируем запрос для текущего расчетного перирода
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Номер месяца' => date('m'),
            'Год' => date('Y'),
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $indications = Yii::$app->client_api->getPreviousCounters($data_json);
        
        return $this->render('index', [
            'indications' => $indications ? $indications : null,
            'comments_to_counters' => $comments_to_counters,
            'is_current' => $is_current,
            'auto_request' => $auto_request,
        ]);

        
    }
    
    /*
     * Отправка показаний приборов учета
     */
    public function actionSendIndication($counter, $indication) {
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            
            $array = [
                "ID" => $counter,
                "Дата снятия показания" => date('Y-m'),
                "Текущее показание" => $indication,
            ];
    
            $array_request['Приборы учета'] = [
                $array,
            ];
            
            $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
            
            $result = Yii::$app->client_api->setCurrentIndications($data_json);
            
            if (!$result) {
                Yii::$app->session->setFlash('error', ['message' => 'Ошибка отправки показания']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            
            Yii::$app->session->setFlash('success', ['message' => 'Показания были успешно отправлены']);
            return $this->redirect(Yii::$app->request->referrer);
        }
        
    }
    
    /*
     * Формирование автоматической заявки на платную услугу
     * Наименование услуги: Поверка приборов учета
     */
    public function actionCreatePaidRequest() {
        
        $account_id = Yii::$app->request->post('accountID');
        $counter_type = Yii::$app->request->post('typeCounter');
        $counter_id = Yii::$app->request->post('idCounter');
        
        Yii::$app->response->format = Response::FORMAT_JSON;        
        
        if (!is_numeric($account_id) && !is_string($counter_type || !is_numeric($counter_id))) {
            return ['success' => false];
        }
        
        if (Yii::$app->request->isAjax) {
            
            $result = $new_request = PaidServices::automaticRequest($account_id, 'Поверка', $counter_type, $counter_id);
            
            if (!$result) {
                return ['success' => false];
            }
            
            return ['success' => true, 'request_number' => $result];
        }
        return ['success' => false];
    }
    
    /*
     * Запрос предыдущих показаний приборов учета
     */
    public function actionFindIndications($month, $year) {
        
        $account_number = $this->_current_account_number;
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!is_numeric($month) || !is_numeric($year) || !isset($month, $year)) {
            return ['success' => false];
        }
        
        if (Yii::$app->request->isPost) {
            
            // Формируем запрос в массиве
            $array_request = [
                'Номер лицевого счета' => $account_number,
                'Номер месяца' => $month,
                'Год' => $year,
            ];
        
            // Преобразуем массив в формат JSON
            $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
            
            $indications = Yii::$app->client_api->getPreviousCounters($data_json);
            
            $data = $this->renderPartial('data/grid-counters', [
                'indications' => $indications ? $indications : null,
                'auto_request' => null,
                'is_current' => false,
                'model_indication' => null,
            ]);
            return ['success' => true, 'result' => $data];
        }
        return ['success' => false];
        
    }
    
    
}
