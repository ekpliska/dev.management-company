<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

class Answers extends ActiveRecord
{
    // Голос "За"
    const ANSWER_BEHIND = 2;
    // Голос "Против"
    const ANSWER_AGAINST = 1;
    // Голос "Воздержаться"    
    const ANSWER_ABSTAIN = 0;
    
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
     * Связь с таблцией Вопросы
     */
    public function getQuestion() {
        return $this->hasOne(Questions::className(), ['questions_id' => 'answers_questions_id']);
    }
    
    /*
     * Варианты голосов
     */
    public static function getAnswersArray() {
        return [
            self::ANSWER_BEHIND => 'За',
            self::ANSWER_AGAINST => 'Против',
            self::ANSWER_ABSTAIN => 'Воздержаться',
        ];
    }
    
    /*
     * Сохраняем ответ пользователя
     */
    public static function sendAnswer($question_id, $type_answer) {
        
        $user_id = Yii::$app->user->identity->id;
        
        $result = self::find()
                ->where(['answers_questions_id' => $question_id, 'answers_user_id' => $user_id])
                ->one();
        
        if ($result == null) {
            // Создаем новую запись
            $new_answer = new Answers();
            $new_answer->answers_questions_id = $question_id;
            $new_answer->answers_vote = $type_answer;
            $new_answer->answers_user_id = $user_id;
            return $new_answer->save() ? true : false;
        } else {
            // Перезаписываем ответ
            $result->answers_vote = $type_answer;
            return $result->save() ? true : false;
        }
        
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
