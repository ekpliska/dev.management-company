<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Questions;

/**
 * Голосование
 */
class Voting extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'voiting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['voiting_type', 'voiting_title', 'voiting_text', 'voiting_object', 'voiting_user_id'], 'required'],
            [['voiting_type', 'voiting_object', 'status', 'voiting_user_id'], 'integer'],
            [['voiting_text'], 'string'],
            [['voiting_date_start', 'voiting_date_end', 'created_at', 'updated_at'], 'safe'],
            [['voiting_title'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * Связь с таблицей Вопросы
     */
    public function getQuestion()
    {
        return $this->hasMany(Questions::className(), ['questions_voiting_id' => 'voiting_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'voiting_id' => 'Voiting ID',
            'voiting_type' => 'Voiting Type',
            'voiting_title' => 'Voiting Title',
            'voiting_text' => 'Voiting Text',
            'voiting_date_start' => 'Voiting Date Start',
            'voiting_date_end' => 'Voiting Date End',
            'voiting_object' => 'Voiting Object',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'voiting_user_id' => 'Voiting User ID',
        ];
    }

}
