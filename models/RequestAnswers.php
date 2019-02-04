<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;

/**
 * Ответы на вопросы к заявке
 */
class RequestAnswers extends ActiveRecord {

    const ANSWER_YES = 2;
    const ANSWER_NO = 1;


    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'request_answers';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['anwswer_question_id', 'anwswer_request_id', 'answer_value'], 'required'],
            [['anwswer_question_id', 'anwswer_request_id', 'answer_value'], 'integer'],
            [['anwswer_question_id'], 'exist', 'skipOnError' => true, 'targetClass' => RequestQuestions::className(), 'targetAttribute' => ['anwswer_question_id' => 'question_id']],
        ];
    }
    
    /**
     * Свзяь с таблицей вопросы
     */
    public function getAnwswerQuestion() {
        return $this->hasOne(RequestQuestions::className(), ['question_id' => 'anwswer_question_id']);
    }

    /**
     * Свзяь с таблицей Заявки
     */
    public function getAnwswerRequest() {
        return $this->hasMany(PaidServices::className(), ['services_id' => 'anwswer_request_id']);
    }
    
    public static function getAnswerArray() {
        
        return [
            self::ANSWER_YES => 'Да',
            self::ANSWER_NO => 'Нет',
        ];
        
        return ArrayHelper::getValue(self::getStatusNameArray(), $status);
        
    }
    
    /*
     * Текстовое представление ответа
     */
    public static function getAnswerText($value) {
        
        return ArrayHelper::getValue(self::getAnswerArray(), $value);
        
    }
    
    /*
     * Добавляем ответ пользователя
     */
    public static function setAnswer($request_id, $question_id, $answer) {
        
        $record = new RequestAnswers();
        $record->anwswer_question_id = $question_id;
        $record->anwswer_request_id = $request_id;
        $record->answer_value = $answer;
        return $record->save() ? true : false;
        
    }
    
    /**
     * Атрибуты полей
     */
    public function attributeLabels() {
        return [
            'answer_id' => 'Answer ID',
            'anwswer_question_id' => 'Anwswer Question ID',
            'anwswer_request_id' => 'Anwswer Request ID',
            'answer_value' => 'Answer Value',
        ];
    }    
    
}
