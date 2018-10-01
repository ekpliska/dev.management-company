<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Rubrics;

class News extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['news_type_rubric_id', 'news_title', 'news_text', 'news_preview', 'news_house_id', 'news_user_id', 'isPrivateOffice', 'created_at', 'updated_at'], 'required'],
            [['news_type_rubric_id', 'news_house_id', 'news_user_id', 'isPrivateOffice', 'isSMS', 'isEmail', 'isPush', 'created_at', 'updated_at'], 'integer'],
            [['news_text'], 'string'],
            [['news_title', 'news_preview'], 'string', 'max' => 255],
            [['news_type_rubric_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rubrics::className(), 'targetAttribute' => ['news_type_rubric_id' => 'rubrics_id']],
        ];
    }
    
    /**
     * Связь с таблицей Рубрика (Тип публикации)
     */
    public function getRubric() {
        return $this->hasOne(Rubrics::className(), ['rubrics_id' => 'news_type_rubric_id']);
    }

    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'news_id' => 'News ID',
            'news_type_rubric_id' => 'News Type Rubric ID',
            'news_title' => 'News Title',
            'news_text' => 'News Text',
            'news_preview' => 'News Preview',
            'news_house_id' => 'News House ID',
            'news_user_id' => 'News User ID',
            'isPrivateOffice' => 'Is Private Office',
            'isSMS' => 'Is Sms',
            'isEmail' => 'Is Email',
            'isPush' => 'Is Push',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
