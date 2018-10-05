<?php

    namespace app\models;
    use Yii;
    use yii\db\Expression;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use app\models\Questions;

/**
 * Голосование
 */
class Voting extends ActiveRecord
{
    
    // Активено
    const STATUS_ACTIVE = 0;
    // Завершено
    const STATUS_CLOSED = 1;
    // Отменено
    const STATUS_CANCEL = 2;

    const TYPE_ALL_HOUSE = 0;
    const TYPE_PORCH = 1;

    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'voting';
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression("'" . date('Y-m-d H:i:s') . "'"),
            ]
        ];
    }
    
    /**
     * Правила вадилации
     */
    public function rules()
    {
        return [
            [['voting_type', 'voting_title', 'voting_text', 'voting_object', 'voting_user_id'], 'required'],
            [['voting_type', 'voting_object', 'status', 'voting_user_id'], 'integer'],
            [['voting_text'], 'string'],
            [['voting_date_start', 'voting_date_end', 'created_at', 'updated_at'], 'safe'],
            [['voting_title'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * Связь с таблицей Вопросы
     */
    public function getQuestion()
    {
        return $this->hasMany(Questions::className(), ['questions_voting_id' => 'voting_id']);
    }
    
    /*
     * Тип голосования
     */
    public static function getTypeVoting() {
        return [
            self::TYPE_ALL_HOUSE => 'Для дома',
            self::TYPE_PORCH => 'Подьезд',
        ];
    }
    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'voting_id' => 'Voiting ID',
            'voting_type' => 'Voiting Type',
            'voting_title' => 'Voiting Title',
            'voting_text' => 'Voiting Text',
            'voting_date_start' => 'Voiting Date Start',
            'voting_date_end' => 'Voiting Date End',
            'voting_object' => 'Voiting Object',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'voting_user_id' => 'Voiting User ID',
        ];
    }

}
