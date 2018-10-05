<?php

    namespace app\models;
    use yii\db\ActiveRecord;
    use Yii;
    use app\models\Questions;

/**
 * Вопросы к голосованию
 */
class Answers extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'answers';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['answers_questions_id', 'answers_vote', 'answers_user_id'], 'required'],
            [['answers_questions_id', 'answers_vote', 'answers_user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['answers_questions_id'], 'exist', 'skipOnError' => true, 'targetClass' => Questions::className(), 'targetAttribute' => ['answers_questions_id' => 'questions_id']],
        ];
    }

    /**
     * Связь с талицей Вопросы
     */
    public function getQuestion()
    {
        return $this->hasOne(Questions::className(), ['questions_id' => 'answers_questions_id']);
    }
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'answers_id' => 'Answers ID',
            'answers_questions_id' => 'Answers Questions ID',
            'answers_vote' => 'Answers Vote',
            'answers_user_id' => 'Answers User ID',
            'created_at' => 'Created At',
        ];
    }

}
