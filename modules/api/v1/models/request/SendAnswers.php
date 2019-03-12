<?php

    namespace app\modules\api\v1\models\request;
    use app\models\RequestAnswers;
    use app\models\Requests;
    use app\models\StatusRequest;

/**
 * Отправка ответов
 */
class SendAnswers extends RequestAnswers {
    
    /*
     * Отправка оценки
     */
    public function send($data_answers) {
        
        if (empty($data_answers['request_id'])) {
            return false;
        }
        
        $request_info = Requests::findByID($data_answers['request_id']);
        
        if (empty($request_info) || $request_info->status != StatusRequest::STATUS_CLOSE) {
            return false;
        }
        
        // Максимальная оценка
        $max_grade = 5;
        // Собираем положительные ответы
        $_grade = 0;
        // Количество вопросов
        $count_answer = count($data_answers['answers']);
        
        foreach ($data_answers['answers'] as $key => $answer) {
            $new_answer = new RequestAnswers();
            $new_answer->anwswer_question_id = $key;
            $new_answer->anwswer_request_id = $data_answers['request_id'];
            $new_answer->answer_value = $answer['value'];
            if ($answer['value'] == RequestAnswers::ANSWER_YES) {
                $_grade++;
            }
            $new_answer->save(false);
        }
        
        // Вычисляем оценку по 5тибальной системе
        $grade = round($max_grade/($count_answer/$_grade), 0);
        // Сохраняем оценку
        $request_info->requests_grade = $grade;
        
        return $request_info->save(false) ? true : false;
        
    }
    
}
