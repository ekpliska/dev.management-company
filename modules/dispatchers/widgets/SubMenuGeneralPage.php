<?php

    namespace app\modules\dispatchers\widgets;
    use yii\base\Widget;
    use app\models\Requests;
    use app\models\PaidServices;
    use app\models\StatusRequest;

/**
 * Нафигационное меню, главная страница
 */
class SubMenuGeneralPage extends Widget {
    
    public $count_requests = 0;
    public $count_paid_requests = 0;


    public $general_navbar = [
        'requests' => 'Заявки',
        'paid-requests' => 'Услуги',
    ];
    
    public function init() {
        
        parent::init();
        
        $this->count_requests = Requests::find()->where(['!=', 'status', StatusRequest::STATUS_CLOSE])->count();
        $this->count_paid_requests = PaidServices::find()->where(['!=', 'status', StatusRequest::STATUS_CLOSE])->count();
        
    }
    
    public function run() {
        
        return $this->render('submenugeneralpage/default', [
            'general_navbar' => $this->general_navbar,
            'count_requests' => $this->count_requests,
            'count_paid_requests' => $this->count_paid_requests,
        ]);
    }
    
}
