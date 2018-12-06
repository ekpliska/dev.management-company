<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\StatusRequest as ModelStatusRequest;
    use app\models\Requests;

/**
 * Виджет для формирования статусов заявок
 */
class StatusRequest extends Widget {
    
    public $account_id;

    public $css_classes = [
        'req-bange req-bange-new', 
        'req-bange req-bange-work', 
        'req-bange req-bange-complete', 
        'req-bange req-bange-rectification', 
        'req-bange req-bange-close'
    ];
    
    public function init() {
        
        if ($this->account_id === null) {
            throw new \yii\base\InvalidConfigException('Не передан обязательный параметр номер лицевого счета');
        }
        parent::init();
    }
    
    public function run() {
        
        // Массив статусов заявок
        $status_requests = ModelStatusRequest::getUserStatusRequests();
        // Новый массив, который будет содержать статус заявки и количество заявок по каждому статусу
        $request_array = [];
        
        foreach ($status_requests as $key => $status) {
            $arrya_count[] = $key;
            $array_count['name'] = $status;
            $array_count['count'] = $this->countRequestToStatus($this->account_id, $key);
            
            $request_array[] = $array_count;
        }
        
        return $this->render('statusrequest/default', [
            'status_requests' => $request_array,
            'css_classes' => $this->css_classes,
        ]);
    }
    
    /*
     * Подсчет количества заявко по каждому статусу
     */
    public function countRequestToStatus($account_id, $status) {
        
        $count = Requests::find()
                ->where(['requests_account_id' => $account_id])
                ->andWhere(['status' => $status])
                ->asArray()
                ->count();
        
        return $count;
    }
    
}
