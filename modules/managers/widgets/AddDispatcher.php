<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;
    use app\modules\managers\models\Dispatchers;

/**
 * Назначение диспетчера в заявке
 */
class AddDispatcher extends Widget {
    
    public $dispatcher_list = [];

    public function init() {
        
        $this->dispatcher_list = Dispatchers::getListDispatchers()->all();
        
        if ($this->dispatcher_list == null) {
            throw new \yii\base\InvalidConfigException('Что-то пошло не так');
        }
        
        parent::init();
    }
    
    public function run() {
        
        return $this->render('adddispatcher/dispatchers_list', [
            'dispatcher_list' => $this->dispatcher_list,
        ]);
        
    }
    
}
