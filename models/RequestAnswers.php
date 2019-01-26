<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "request_answers".
 *
 * @property int $answer_id
 * @property int $anwswer_question_id
 * @property int $anwswer_request_id
 * @property int $answer_value
 *
 * @property RequestQuestions $anwswerQuestion
 * @property PaidServices $anwswerRequest
 */
class RequestAnswers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_answers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['anwswer_question_id', 'anwswer_request_id', 'answer_value'], 'required'],
            [['anwswer_question_id', 'anwswer_request_id', 'answer_value'], 'integer'],
            [['anwswer_question_id'], 'exist', 'skipOnError' => true, 'targetClass' => RequestQuestions::className(), 'targetAttribute' => ['anwswer_question_id' => 'question_id']],
            [['anwswer_request_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaidServices::className(), 'targetAttribute' => ['anwswer_request_id' => 'services_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'answer_id' => 'Answer ID',
            'anwswer_question_id' => 'Anwswer Question ID',
            'anwswer_request_id' => 'Anwswer Request ID',
            'answer_value' => 'Answer Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnwswerQuestion()
    {
        return $this->hasOne(RequestQuestions::className(), ['question_id' => 'anwswer_question_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnwswerRequest()
    {
        return $this->hasOne(PaidServices::className(), ['services_id' => 'anwswer_request_id']);
    }
    
    public static function setAnswer($request_id, $question_id, $answer) {
        
        $record = new RequestAnswers();
        $record->anwswer_question_id = $question_id;
        $record->anwswer_request_id = $request_id;
        $record->answer_value = $answer;
        return $record->save() ? true : false;
        
    }
}
