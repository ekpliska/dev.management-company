<?php

    namespace app\modules\api\v1\models\vote;
    use Yii;
    use app\models\Voting;
    use app\models\Houses;

/**
 * Опрос
 */
class VoteList extends Voting {
    
    /*
     * Все опросы для текущего пользователя
     * @param integer $account
     */
    public static function getFullVoteList($account) {
        
        $results = [];
        
        $house_info = Houses::find()
                ->joinWith([
                    'flat',
                    'flat.account' => function ($query) use ($account) {
                        $query->andWhere(['account_number' => $account]);
                    }], false)
                ->one();
        
        if (empty($house_info->houses_id)) {
            return false;
        }
        
        $vote_lists = self::find()
                ->with(['participant'])
                ->where(['voting_type' => 'all'])
                ->orWhere(['voting_house_id' => $house_info->houses_id])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
        
        
        foreach ($vote_lists as $key_vote => $vote_list) {
            // Тело опроса
            $_vote = [
                'vote_id' => $vote_list->voting_id,
                'vote_title' => $vote_list->voting_title,
            ];
            
            // Участники опроса, которые завершили голосование
            $participants = [];
            foreach ($vote_list->participant as $key_participant => $participant) {
                $_participant[$key_participant] = [
                    'user_id' => $participant->user->user_id,
                    'user_image' => $participant->user->photo,
                ];
                $participants = $_participant;
            }
            $_participant = null;
            
            // Результат
            $results[] = [
                'vote' => $_vote,
                'participants' => $participants,
            ];

        }
        
        return $results;
        
    }
    
}
