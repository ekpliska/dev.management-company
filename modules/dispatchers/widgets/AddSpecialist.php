<?php

    namespace app\modules\dispatchers\widgets;
    use yii\base\Widget;
    use app\modules\dispatchers\models\Specialists;

/**
 * Назначение специалиста
 */
class AddSpecialist extends Widget {
    
    public $specialist_list = [];
    
    public function init() {
        
        $this->specialist_list = Specialists::getListSpecialists()->all();
        
        parent::init();
    }
    
    public function run() {
        
        return $this->render('addspecialist/specialist_list', [
            'specialist_list' => $this->specialist_list,
        ]);
        
    }
    
    
}
