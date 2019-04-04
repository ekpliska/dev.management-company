<?php

    namespace app\modules\api\v1\models\vote;
    use Yii;
    use app\models\Voting;
    use app\models\Houses;
    use app\models\RegistrationInVoting;
    
/**
 * Опрос
 */
class VoteList extends Voting {
    
    /*
     * Все опросы для текущего пользователя
     * @param integer $account Текущий лицевой счет
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
            
            // Участники опроса, которые завершили голосование
            $participants = [];
            $status_current_user = null;
            foreach ($vote_list->participant as $key_participant => $participant) {
                
                $_participant[$key_participant] = [
                    'user_id' => $participant->user->user_id,
                    'user_image' => $participant->user->photo,
                ];
                $participants = $_participant;
                if ($participant->user->user_id == Yii::$app->user->getId()) {
                    $status_current_user = ($participant->finished == RegistrationInVoting::STATUS_FINISH_YES) ? 'finished' : $participant->status;
                }
            }
            $_participant = null;
            
            // Тело опроса
            $_vote = [
                'vote_id' => $vote_list->voting_id,
                'vote_image' => $vote_list->voting_image,
                'vote_title' => $vote_list->voting_title,
                'voting_text' => $vote_list->voting_text,
                'vote_date' => $vote_list->created_at,
                'status' => $status_current_user,
            ];            
            
            // Результат
            $results[] = [
                'vote' => $_vote,
                'participants' => $participants,
            ];
        }
        
        return $results;
        
    }
    
}