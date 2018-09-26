<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;
    use app\models\StatusRequest;

/**
 *  Переключатель статуса заявок
 */
class SwitchStatusRequest extends Widget {
    
    public $status;
    public $request_id;
    public $array_status;


    public function init() {
        
        $this->array_status = StatusRequest::getStatusNameArray();
        
        if ($this->status == null && $this->request_id == null) {
            throw new \yii\base\InvalidConfigException('Параметр status не задан');
        }
        
        parent::init();
    }
    
    public function run() {
        return $this->render('switchstatusrequest/request', [
            'status' => $this->status,
            'request' => $this->request_id,
            'array_status' => $this->array_status,
        ]);
    }
    
}
