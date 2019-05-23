<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\News;
    use yii\data\Pagination;
    

/* 
 * Виджет для отрисовки последних актуальных новостей
 */

class LastNews extends Widget {
    
    public $living_space;
    // Количество Новостей на главной
    public $count_news = 12;
    
    public function init() {
        parent::init();
    }
    
    public function run() {
        
        // Формируем список новостей для текущено пользователя
        $news = News::getNewsByClients($this->living_space)->limit($this->count_news)->all();
        
        return $this->render('lastnews/default', [
            'news' => $news,
        ]);
        
    }

}
