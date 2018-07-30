<?php
    
    namespace app\widgets;
    use yii\base\InvalidConfigException;
    use yii\base\Widget;

/*
 * Виджет формирования модального окна "Добавить арендатора"
 */

class AddRentForm extends Widget {
    
    // Имя модели Новый арендатор
    public $add_rent;

    public function init() {

        if ($this->add_rent == null) {
            throw new InvalidConfigException('Ошибка передачи параметра $add_rent');
        }
        parent::init();
        
    }
    
    public function run() {
        return $this->render('addrentform/addrentform', ['add_rent' => $this->add_rent]);
    }
    
}
