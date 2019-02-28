<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;
    use app\modules\managers\models\Dispatchers;
    use app\modules\managers\models\Specialists;

/**
 * Назначение диспетчера в заявке
 */
class AddEmployee extends Widget {
    
    /*
     * Тип завки 
     * requests => Заявка
     * paid_requests => Заявка на платную услугу
     */
    public $type;
    
    public $dispatcher_list = [];
    public $specialist_list = [];
    

    public function init() {
        
        $this->dispatcher_list = Dispatchers::getListDispatchers()->all();
        $this->specialist_list = Specialists::getListSpecialists()->all();
        
        parent::init();
    }
    
    public function run() {
        
        return $this->render('addemployee/employee_list', [
            'dispatcher_list' => $this->dispatcher_list,
            'specialist_list' => $this->specialist_list,
            'type' => $this->type,
        ]);
        
    }
    
}
