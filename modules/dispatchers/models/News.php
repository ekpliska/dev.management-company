<?php

    namespace app\modules\dispatchers\models;
    use app\models\News as BaseNews;

/**
 * Новости
 */
class News extends BaseNews {
    
    
    /*
     * Вывод списка последних новостей для главной страницы Диспетчеры
     * @param integer $count_news Количество новостей на странице
     */
    public static function getNewsList($count_news) {
        
        $querty = self::find()
                ->limit($count_news)
                ->asArray()
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
        
        
        return $querty;
    }
    
}
