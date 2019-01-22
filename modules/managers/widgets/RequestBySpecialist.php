<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;
    use yii\base\InvalidConfigException;
    use app\modules\managers\models\Requests;
    use app\modules\managers\models\PaidServices;

/**
 * Виджет вывода заявок и заявки на платные услуги назначенные специалисту
 */
class RequestBySpecialist extends Widget {
    
    public $employee_id;
    
    // Заявки
    public $requests_list;
    // Платные услуги
    public $paid_requests_list;
    
    public function init() {
        
        if ($this->employee_id == null) {
            throw new InvalidConfigException('Ошибка передачи параметра $employee_id');
        }
        
        $this->requests_list = Requests::getRequestBySpecialist($this->employee_id);
        $this->paid_requests_list = PaidServices::getPaidRequestBySpecialist($this->employee_id);
        
        parent::init();
        
    }
    
    public function run() {
        
        return $this->render('requestbyspecialist/default', [
            'requests_list' => $this->requests_list,
            'paid_requests_list' => $this->paid_requests_list,
        ]);
        
    }
    
}
