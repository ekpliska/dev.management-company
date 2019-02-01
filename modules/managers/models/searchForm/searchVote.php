<?php

    namespace app\modules\managers\models\searchForm;
    use app\models\Voting;

/**
 * Форма поиска опросов, через дополнительное навигационное меню
 */
class searchVote extends Voting {
    
    public $value;
    
    public function rules() {
        
        return [
            [['value'], 'string'],
            [['value'], 'trim'],
            
            [['voting_house_id'], 'integer'],
            
        ];
        
    }
    
    public function search($params) {
        
        $query = Voting::find()
                ->joinWith('registration')
                ->orderBy(['voting_date_start' => SORT_DESC]);
        
        $this->load($params);
        
        if (!$this->validate()) {
            return $query;
        }
        
        $query->andFilterWhere(['like', 'voting_title', $this->value]);
        
        $query->andFilterWhere(['like', 'voting_house_id', $this->voting_house_id]);
        
        return $query;
        
    }
    
    
}
