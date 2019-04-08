<?php

    namespace app\modules\api\v1\models\vote;
    use app\models\Questions;

/**
 * Вопросы к опросы
 */
class QuestionList extends Questions {
    
    /*
     * Список вопросов к опросу
     */
    public static function getQuestions($vote_id) {
        
        $questions = self::find()
                ->select(['questions_id', 'questions_text'])
                ->where(['questions_voting_id' => $vote_id])
                ->all();
        
        return $questions ? $questions : false;
        
    }
    
}
