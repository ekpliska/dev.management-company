<?php

    namespace app\modules\api\v1\models\vote;
    use Yii;
    use yii\helpers\ArrayHelper;
    use app\models\Voting;
    use app\models\Houses;
    use app\models\RegistrationInVoting;
    use app\models\Answers;
    
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
                
                $_participant = $participant->user->photo;
                $participants[] = $_participant;
                if ($participant->user->user_id === Yii::$app->user->getId()) {
                    if ($participant->finished == RegistrationInVoting::STATUS_FINISH_YES) {
                        $status_current_user = 'finished';
                    } elseif ($participant->finished == RegistrationInVoting::STATUS_FINISH_NO) {
                        $status_current_user = 'participant';
                    } else {
                        $status_current_user = null;
                    }
                }
            }
            $_participant = null;
            
            // Тело опроса
            $_vote = [
                'vote_id' => $vote_list->voting_id,
                'vote_image' => $vote_list->voting_image,
                'vote_title' => $vote_list->voting_title,
                'voting_text' => $vote_list->voting_text,
                'vote_date' => $vote_list->voting_date_end,
                'is_opened' => $vote_list->status == 0 ? true : false,
                'participant_status' => $status_current_user,
            ];            
            
            // Результат
            $results[] = [
                'vote' => $_vote,
                'participants' => $participants,
            ];
        }
        
        return $results;
        
    }
    
    /*
     * Проверить, является ли текущий пользователь 
     * зарегистрированным участником
     */
    public static function isParticipant($vote_id) {
        
        $is_participant = RegistrationInVoting::find()
                ->where([
                    'voting_id' => $vote_id,
                    'user_id' => Yii::$app->user->getId(),
                    'status' => RegistrationInVoting::STATUS_ENABLED])
                ->one();

        return $is_participant ? true : false;
    }
    
    /*
     * Регистрация участника опроса
     */
    public static function registerToVote($vote_id) {
        
        // Проверяем судествование опроса
        if (!self::findOne(['voting_id' => $vote_id])) {
            return false;
        }

        // Проверяем не был ли пользователь зарегистрирован раньше
        if (self::isParticipant($vote_id)) {
            return false;
        }
        
        $model = new RegistrationInVoting();
        $model->voting_id = $vote_id;
        $model->user_id = Yii::$app->user->getId();
        $model->random_number = 0;
        $model->status = RegistrationInVoting::STATUS_ENABLED;
        $model->date_registration = time();
        return $model->save(false) ? true : false;
        
    }
    
    /*
     * Отправка ответов
     */
    public static function sendAnswer($data_post) {
        
        if (empty($data_post)) {
            return false;
        }
        
        $type_answer = Answers::getAnswersArray();
        
        foreach ($data_post['answers'] as $key => $answer) {
            $model = new Answers();
            $model->answers_questions_id = $key;
            if (!ArrayHelper::keyExists($answer, $type_answer)) {
                return false;
            }
            $model->answers_vote = $answer;
            $model->answers_user_id = Yii::$app->user->getId();            
            $model->save();
        }
        
        $finish = RegistrationInVoting::find()
                ->where(['voting_id' => $data_post['vote_id']])
                ->andWhere(['user_id' => Yii::$app->user->getId()])
                ->one();
        $finish->finished = RegistrationInVoting::STATUS_FINISH_YES;        
        $finish->save(false);
        
        return true;
        
    }
    
}