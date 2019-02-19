<?php

    namespace app\modules\dispatchers\models\searchForm;
    use app\models\News;

/**
 * Форма поиска по заявкам, через дополнительное навигационное меню
 */
class searchNews extends News {
    
    public $value;
    public $date_start;
    public $date_finish;
    
    public function rules() {
        
        return [
            
            ['news_title', 'string', 'max' => 150],
            
            ['isAdvert', 'integer'],
            
            [['news_house_id'], 'integer'],
            
            [['date_start', 'date_finish'], 'date', 'format' => 'php:Y-m-d']
            
        ];
        
    }
    
    public function search($params) {
        
        $query = (new \yii\db\Query)
                ->select('news_id as id, '
                        . 'rubrics_name as rubric, '
                        . 'news_title as title, news_text as text,'
                        . 'news_preview as preview, '
                        . 'news_house_id as house, '
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
                ->orderBy(['created_at' => SORT_DESC]);
        
        $this->load($params);
        
        if (!$this->validate()) {
            return $query;
        }
        
        $query->andFilterWhere(['like', 'news_title', $this->news_title]);
        
        $query->andFilterWhere(['=', 'isAdvert', $this->isAdvert]);
        
        
        if (!empty($this->date_start) && !empty($this->date_finish)) {
            $query->andFilterWhere(['between', 'created_at', $this->date_start, $this->date_finish]);
        }
        
        $query->andFilterWhere(['like', 'news_house_id', $this->news_house_id]);
        
        return $query;
        
    }
    
    
}
