<?php

    namespace app\modules\api\v1\models\vote;
    use Yii;
    use app\models\Answers;
    use app\models\Questions;

/**
 * Результаты по текущему опросу
 */
class ResultsVote extends Answers {
    
    public function getResults($vote_id) {
        
        $vote_info = Questions::find()
                ->joinWith(['answer'])
                ->where(['questions_voting_id' => $vote_id])
                ->orderBy(['questions_id' => SORT_ASC])
                ->asArray()
                ->all();
        
        // Массив, где будем формировать результаты голосования в процентном соотношении к каждому типу голоса
        $results = [];
        
        foreach ($vote_info as $question_key => $question) {
            $against_count = 0;
            $behind_count = 0;
            $abstain_count = 0;
            $count = count($question['answer']);
            foreach ($question['answer'] as $answer_key => $answer) {
                if ($answer['answers_vote'] == 'behind') { $against_count++; }
                if ($answer['answers_vote'] == 'against') { $behind_count++; }
                if ($answer['answers_vote'] == 'abstain') { $abstain_count++; }
            }
            // Массив формирует количество ответов (в %) каждого типа по текущему вопросы
            $ansqwers_count = [
                'behind' => $count == 0 ? '0' : (string)round(($against_count * 100) / $count),
                'against' => $count == 0 ? '0' : (string)round(($behind_count * 100) / $count),
                'abstain' => $count == 0 ? '0' : (string)round(($abstain_count * 100) / $count),
            ];
            // Формируем массив по каждому ответу
            $result = [
                'text_question' => $question['questions_text'],
                'count' => (string)$count,
                'answers' => $ansqwers_count,
            ];
            $results[] = $result;
        }
        
        return $results;
        
    }
    
}
