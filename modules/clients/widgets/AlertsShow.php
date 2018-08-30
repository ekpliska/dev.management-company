<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;

/**
 * Description of AlertsShow
 *
 * @author Ekaterina
 */
class AlertsShow extends Widget {
    
    public function run() {
        
        return $this->render('alertsshow/view');
        
    }
}
