<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Voting;
    use app\models\Answers;

/**
 * Вопросы к голосованию
 */
class Questions extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'questions';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['questions_voiting_id', 'questions_text', 'questions_user_id'], 'required'],
            [['questions_voiting_id', 'questions_user_id'], 'integer'],
            [['questions_text'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['questions_voiting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Voiting::className(), 'targetAttribute' => ['questions_voiting_id' => 'voiting_id']],
        ];
    }
    
    /**
     * Связь с таблицей Ответы
     */
    public function getAnswer()
    {
        return $this->hasMany(Answers::className(), ['answers_questions_id' => 'questions_id']);
    }

    /**
     * Связь с таблицей Голосование
     */
    public function getVoiting()
    {
        return $this->hasOne(Voting::className(), ['voiting_id' => 'questions_voiting_id']);
    }

    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'questions_id' => 'Questions ID',
            'questions_voiting_id' => 'Questions Voiting ID',
            'questions_text' => 'Questions Text',
            'questions_user_id' => 'Questions User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
