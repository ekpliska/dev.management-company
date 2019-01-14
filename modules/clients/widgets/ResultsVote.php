<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\Voting;

/**
 * Description of ResultsVote
 *
 * @author dreamer
 */
class ResultsVote extends Widget {

    public $voting_id;
    
    public $_vote_info;


    public function init() {
        
        if ($this->voting_id === null) {
            throw new \yii\base\InvalidConfigException('Отсутствует обязательный параметр $voting_id');
        }
        
        $this->_vote_info = \app\models\Questions::find()
                ->joinWith(['answer'])
                ->where(['questions_voting_id' => $this->voting_id])
                ->orderBy(['questions_id' => SORT_ASC])
                ->asArray()
                ->all();
        
        parent::init();
        
    }
    
    public function run() {
        
        $vote_info = $this->_vote_info;
        $results = [];
        
        foreach ($vote_info as $question_key => $question) {
            $against_count = 0;
            $behind_count = 0;
            $abstain_count = 0;
            $count = count($question['answer']);
            foreach ($question['answer'] as $answer_key => $answer) {
                if ($answer['answers_vote'] == 'against') { $against_count++; }
                if ($answer['answers_vote'] == 'behind') { $behind_count++; }
                if ($answer['answers_vote'] == 'abstain') { $abstain_count++; }
            }
            $ansqwers_count = [
                'against' => $against_count,
                'behind' => $behind_count,
                'abstain' => $abstain_count,
            ];
            $result = [
                'text_question' => $question['questions_text'],
                'count' => $count,
                'answers' => $ansqwers_count,
            ];
            $results[] = $result;
        }
        
        return $this->render('resultsvote/default', [
            'results' => $results,
        ]);
    }
    
}
