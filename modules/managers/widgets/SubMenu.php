<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;

/**
 * Дополнительное под меню
 */
class SubMenu extends Widget {
    
    public $view_name = '';
    
    public function init() {
        
        if ($this->view_name == null) {
            throw new \yii\base\InvalidConfigException('Не указан вид дополнительного навигационного меню');
        }
        parent::init();        
    }
    
    public function run() {
        
        $params = [];
        
        switch ($this->view_name) {
            case 'clients':
                $params = [];
                break;
        }
        
        return $this->render('submenu/' . $this->view_name);
        
    }
    
}
