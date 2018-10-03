<?php

    namespace app\modules\managers\models;
    use app\models\News as BaseNews;

/**
 * Новости
 */
class News extends BaseNews {
    
    public static function getAllNews($adver) {
        
        $array = (new \yii\db\Query)
                ->select('news_id as id, '
                        . 'rubrics_name as rubric, '
                        . 'news_title as title, news_text as text,'
                        . 'slug as slug, '
                        . 'isAdvert as advert, '
                        . 'created_at as date, '
                        . 'he.estate_name as estate_name, h_e.houses_town as e_town,'
                        . 'h.houses_town as town, h.houses_street as street, h.houses_number_house as house')
                ->from('news as n')
                ->join('LEFT JOIN', 'rubrics as r', 'n.news_type_rubric_id = r.rubrics_id')
                ->join('LEFT JOIN', 'housing_estates as he', 'n.news_house_id = he.estate_id')
                ->join('LEFT JOIN', 'houses as h_e', 'he.estate_id = h_e.houses_estate_name_id')
                ->join('LEFT JOIN', 'houses as h', 'n.news_house_id = h.houses_id')
                ->where(['isAdvert' => $adver])
                ->orderBy(['created_at' => SORT_DESC])
                ->groupBy('news_id');
        
        return $array;
        
    }
    
}
