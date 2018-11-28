<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\StatusRequest as ModelStatusRequest;

/**
 * Виджет для формирования статусов заявок
 */
class StatusRequest extends Widget {
    
    public $status_requests = [];
    public $css_classes = [
        'req-bange req-bange-new', 
        'req-bange req-bange-work', 
        'req-bange req-bange-complete', 
        'req-bange req-bange-rectification', 
        'req-bange req-bange-close'
    ];
    
    public function init() {
        
        $this->status_requests = ModelStatusRequest::getUserStatusRequests();
        parent::init();
    }
    
    public function run() {
        
        return $this->render('statusrequest/default', [
            'status_requests' => $this->status_requests,
            'css_classes' => $this->css_classes,
        ]);
    }
    
}
