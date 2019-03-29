<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;

/* 
 * Виджет для отрисовки последних актуальных новостей
 */

class LastNews extends Widget {
    
    // Количество Новостей на главной
    public $count_news = 12;
    
    public function init() {
        
        parent::init();
        
    }
    
    public function run() {
        
        return $this->render('lastnews/default');
        
    }
    
    
}
