<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\Organizations;

/**
 * Футер
 */
class Footer extends Widget {
    
    public $organizations_info;
    
    public function init() {
        
        $this->organizations_info = Organizations::find()
                ->where(['organizations_id' => 1])
                ->asArray()
                ->one();
        
    }
    
    public function run() {
        
        return $this->render('footer/default', [
           'organizations_info' => $this->organizations_info,
        ]);
        
    }
    
}
