<?php

    namespace app\modules\managers\models;
    use app\models\News as BaseNews;
    use app\models\Voting;

/**
 * Новости
 */
class News extends BaseNews {
    
    public static function getAllNews($adver) {
        
        $array = (new \yii\db\Query)
                ->select('news_id as id, '
                        . 'rubrics_name as rubric, '
                        . 'news_title as title, news_text as text,'
                        . 'news_preview as preview, '
                        . 'slug as slug, '
                        . 'isAdvert as advert, '
                        . 'news_status as status, '
                        . 'created_at as date, '
                        . 'updated_at as date_update, '
                        . 'news_user_id as user_id, '
                        . 'h.houses_gis_adress as houses_gis_adress, h.houses_number as houses_number, '
                        . 'p.partners_name as partners_name')
                ->from('news as n')
                ->join('LEFT JOIN', 'rubrics as r', 'n.news_type_rubric_id = r.rubrics_id')
                ->join('LEFT JOIN', 'houses as h', 'n.news_house_id = h.houses_id')
                ->join('LEFT JOIN', 'partners as p', 'p.partners_id = n.news_partner_id')
                ->where(['isAdvert' => $adver])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
        
        return $array;
        
    }
    
    /*
     * Вывод последних новостей и опросов на главной странице
     */
    public static function getAllNewsAndVoting() {
        
        // Количество блоков для вывода важной информации
        $count_news = 10;
    
        $voling_list = Voting::find()
                ->select(['voting_id as slug', 'voting_image as news_preview', 'voting_title as news_title', 'voting_text as news_text', 'created_at'])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->all();
        
        $news_list = News::find()
                ->select(['news_id', 'news_title', 'news_preview', 'news_text', 'created_at', 'slug'])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->limit($count_news - count($voling_list))
                ->all();
        
        $lists = array_merge($voling_list, $news_list);
        
        // Сортируем полученный массив по дате создания записи
        usort($lists, function ($a, $b) {
            return (strtotime($a['created_at']) < strtotime($b['created_at'])) ? 1 : -1;
        });
        
        return $lists;
    }
    
}
