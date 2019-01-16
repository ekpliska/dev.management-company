<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;
    use app\models\StatusRequest;

/**
 *  Переключатель статуса заявок
 */
class SwitchStatusRequest extends Widget {
    
    public $view_name;
    public $status;
    public $request_id;
    public $date_update;
    public $array_status;


    public function init() {
        
        $this->array_status = StatusRequest::getUserStatusRequests();
        
        if ($this->view_name == null || $this->status == null || $this->request_id == null) {
            throw new \yii\base\InvalidConfigException('Ошибка передачи параметров');
        }
        
        parent::init();
    }
    
    public function run() {
        return $this->render('switchstatusrequest/' . $this->view_name, [
            'status' => $this->status,
            'date_update' => $this->date_update,
            'request' => $this->request_id,
            'array_status' => $this->array_status,
        ]);
    }
    
}
