<?php

    namespace app\modules\api\v1\models;
    use app\models\News as BaseNews;
    use app\models\Voting;

/**
 * Новости
 */
class News extends BaseNews {
    
    public static function importantInformation($house_id) {
        
        $count_news = 9;
        
        $news_list = BaseNews::find()
                ->select(['news_id', 'news_title', 'news_preview', 'LEFT(news_text, 250) as news_text', 'created_at'])
                ->andWhere(['news_house_id' => $house_id])
                ->orderBy(['created_at' => SORT_DESC])
                ->orWhere(['news_status' => 'all'])
                ->asArray()
                ->limit($count_news)
                ->all();
        
        return $news_list;
    }
    
    public static function otherNews($house_id, $is_advert) {
        
        $news = BaseNews::find()
                ->select(['news_id', 'news_title', 'news_preview', 'LEFT(news_text, 120) as news_text', 'created_at'])
                ->andWhere([
                    'news_house_id' => $house_id,
                    'isAdvert' => $is_advert,
                ])
                ->orWhere([
                    'news_status' => 'all',
                    'isAdvert' => $is_advert,
                ])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->all();
        
        return $news;
    }
    
    public static function findNewsByID($id) {
        
        return self::find()
                ->select(['news_id', 'news_title', 'news_preview', 'news_text', 'created_at'])
                ->where(['news_id' => $id])
                ->asArray()
                ->one();
        
    }
    
    
}
