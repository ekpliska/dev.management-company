<?php

    namespace app\modules\dispatchers\widgets;
    use yii\base\Widget;

/**
 * Виджет вывода сообщений для модуля Диспетчер
 */
class AlertsShow extends Widget {
    
    public function run() {
        
        return $this->render('alertsshow/view');
        
    }
}
