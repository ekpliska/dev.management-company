<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\News as ModelNews;

/**
 * Вывод новостей
 */
class News extends Widget {
    
    public $estate_id;
    public $house_id;
    public $flat_id;
    
    public function init() {
        
        if ($this->estate_id === null && $this->house_id === null && $this->flat_id === null) {
            throw new \yii\base\InvalidConfigException('Ошибка передачи параметров');
        }
        
    }
    
    public function run() {
        
        $news = $this->getAllNews($this->estate_id, $this->house_id, $this->flat_id);
        return $this->render('news/default', [
            'news' => $news,
        ]);
    }
    
    private function getAllNews($estate, $house, $flat) {
        $news = ModelNews::find()
                ->select(['news_id', 'news_title', 'news_preview', 'news_text', 'created_at', 'slug', 'rubrics_name', 'isAdvert', 'partners_name'])
                ->joinWith(['rubric', 'partner'])
                ->andWhere(['news_house_id' => $house])
                ->orWhere(['news_house_id' => 0])
                ->asArray()
                ->all();
        
        return $news;
        
    }
    
}
