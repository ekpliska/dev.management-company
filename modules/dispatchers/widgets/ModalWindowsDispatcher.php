<?php

    namespace app\modules\dispatchers\widgets;
    use yii\base\Widget;

/**
 * Виджет управления модальными окнами
 * Модуль "Диспетчеры"
 */
class ModalWindowsDispatcher extends Widget {
    
    // view модального окна
    public $modal_view;

    public function init() {
        
        parent::init();
        
        if ($this->modal_view == null) {
            throw new InvalidConfigException('Ошибка при передаче параметров. Не задан вид модального окна');
        }
        
    }

    public function run() {
        
        return $this->render('modalwindowsdispatcher/'. $this->modal_view, [
            'name_view' => $this->modal_view,]);
        
    }
    
}
