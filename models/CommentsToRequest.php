<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

/**
 * Комментарии к заявкам
 */
class CommentsToRequest extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'comments_to_request';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            ['comments_text', 'required'],
            [['comments_request_id', 'comments_user_id', 'created_at'], 'integer'],
            [['comments_text'], 'string', 'min' => 10, 'max' => 255],
        ];
    }

    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'comments_id' => 'Comments ID',
            'comments_request_id' => 'Comments Request ID',
            'comments_user_id' => 'Comments User ID',
            'comments_text' => 'Comments Text',
            'created_at' => 'Created At',
        ];
    }
}
