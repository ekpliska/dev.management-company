<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;
    use yii\base\InvalidConfigException;
    use app\modules\managers\models\Dispatchers;
    use app\modules\managers\models\Specialists;

/**
 * Назначение диспетчера в заявке
 */
class AddEmployee extends Widget {
    
    public $type_request;
    public $dispatcher_list = [];
    public $specialist_list = [];

    public function init() {
        
        if ($this->type_request == null) {
            throw new InvalidConfigException('Ошибка при передаче параметров. Не задан ID пользователя');
        }
        
        $this->dispatcher_list = Dispatchers::getListDispatchers()->all();
        $this->specialist_list = Specialists::getListSpecialists()->all();
        
        parent::init();
    }
    
    public function run() {
        
        return $this->render('addemployee/employee_list', [
            'type_request' => $this->type_request,
            'dispatcher_list' => $this->dispatcher_list,
            'specialist_list' => $this->specialist_list,
        ]);
        
    }
    
}
