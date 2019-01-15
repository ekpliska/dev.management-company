<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\Questions;
    use app\models\Answers;

/**
 * Виджет вывода результатов голосования
 */
class ResultsVote extends Widget {

    // ID голосования
    public $voting_id;
    
    // Информация по вопросам и ответам голосования
    public $_vote_info;


    public function init() {
        
        if ($this->voting_id === null) {
            throw new \yii\base\InvalidConfigException('Отсутствует обязательный параметр $voting_id');
        }
        
        $this->_vote_info = Questions::find()
                ->joinWith(['answer'])
                ->where(['questions_voting_id' => $this->voting_id])
                ->orderBy(['questions_id' => SORT_ASC])
                ->asArray()
                ->all();
        
        parent::init();
        
    }
    
    public function run() {
        
        $vote_info = $this->_vote_info;
        // Массив, где будем формировать результаты голосования в процентном соотношении к каждому типу голоса
        $results = [];
        
        // Тип ответов
        $type_answers = Answers::getAnswersArray();
        
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
                'behind' => $count == 0 ? '0' : (($against_count * 100) / $count),
                'against' => $count == 0 ? '0' : (($behind_count * 100) / $count),
                'abstain' => $count == 0 ? '0' : (($abstain_count * 100) / $count),
            ];
            // Формируем массив по каждому ответу
            $result = [
                'text_question' => $question['questions_text'],
                'count' => $count,
                'answers' => $ansqwers_count,
            ];
            $results[] = $result;
        }
        
        return $this->render('resultsvote/default', [
            'results' => $results,
            'type_answers' => $type_answers,
        ]);
    }
    
}
