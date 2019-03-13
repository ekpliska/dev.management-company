<?php

    namespace app\modules\api\v1\models\request;
    use app\models\RequestQuestions as BaseRequestQuestions;

class RequestQuestions extends BaseRequestQuestions {
    
    /*
     * Получить список вопросов по указанному типу заявки
     */
    public static function getQuestions($type_name) {
        
        $result = [];
        
        $question_lists = self::find()
                ->joinWith('typeRequest type')
                ->where(['type.type_requests_name' => $type_name])
                ->asArray()
                ->all();
        
        if (empty($question_lists)) {
            return false;
        }
        
        foreach ($question_lists as $key => $question) {
            $result['questions'][] = [
                'question_id' => $question['question_id'],
                'question_text' => $question['question_text']
            ];
        }
        
        return $result;
        
    }
    
}