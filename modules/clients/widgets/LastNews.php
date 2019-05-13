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
        
        // Параметр для слайдера по умолчанию (влияет на отображения новостей при количестве новостей меньше 3)
        $data_merge = 3;
        if (count($news) == 2) {
            $data_merge = 2;
        } elseif (count($news) == 3) {
            $data_merge = 1;
        }
                
        return $this->render('lastnews/default', [
            'news' => $news,
            'data_merge' => $data_merge,
        ]);
        
    }

}
