<?php

    namespace app\modules\api\models;
    use app\models\News as BaseNews;
    use app\models\Voting;

/**
 * Новости
 */
class News extends BaseNews {
    
    public static function importantInformation($house_id) {
        
        $count_news = 9;
        
        $voting_list = Voting::find()
                ->select(['voting_id', 'voting_image', 'voting_title', 'LEFT(voting_text, 120) as voting_text', 'created_at'])
                ->andWhere(['voting_house_id' => $house_id])
                ->orWhere(['voting_type' => 'all'])
                ->andWhere(['status' => false])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->all();
        
        $news_list = BaseNews::find()
                ->select(['news_id', 'news_title', 'news_preview', 'LEFT(news_text, 120) as news_text', 'created_at'])
                ->andWhere([
                    'news_house_id' => $house_id])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->limit($count_news - count($voting_list))
                ->all();
        
        $lists = array_merge($voting_list, $news_list);
        
        // Сортируем полученный массив по дате создания записи
        usort($lists, function ($a, $b) {
            return (strtotime($a['created_at']) < strtotime($b['created_at'])) ? 1 : -1;
        });
        
        return $lists;
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
