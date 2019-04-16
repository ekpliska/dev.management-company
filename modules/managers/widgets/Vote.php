<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;
    use app\models\Answers;

/**
 * Виджет вывод голосов
 */
class Vote extends Widget {
    
    // ID вопроса
    public $question_id;
    // Количество голосов по конкретному вопросу
    public $count;

    public function init() {
        
        if ($this->question_id === null) {
            throw new \yii\base\InvalidConfigException('Ошибка передачи параметров');
        }
        
        parent::init();
        
    }
    
    public function run() {
        
        // Массив, содержит процент проголосовавших по каждому варинту голосоа
        $results = [];
        $answers = Answers::getAnswersArray();
        
        foreach ($answers as $key => $answer) {
            // Получаем по каждому варианту Ответа (За, Против, Воздержались) количество процентов
            $vote = (new \yii\db\Query())
                    ->from('answers')
                    ->where(['answers_questions_id' => $this->question_id])
                    ->andWhere(['answers_vote' => $key])
                    ->count();
            // Если деление на 0
            $result[$answer] = $this->count == 0 ? '0' : ($vote * 100) / $this->count;
            $results = $result;
        }
        
        
        return $this->render('vote/default', [
            'answers' => $answers,
            'results' => $results,
        ]);
        
    }
    
    
}
