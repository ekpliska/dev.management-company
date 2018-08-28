<?php

    namespace app\models;
    use Yii;
    use app\models\Requests;

/**
 * Оценка заявок
 */
class RatingsToRequest extends \yii\db\ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'ratings_to_request';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['ratings_request_id', 'created_at', 'ratings_value', 'user_id'], 'required'],
            [['ratings_request_id', 'created_at', 'ratings_value', 'user_id'], 'integer'],
        ];
    }
    
    /*
     * Связь с таблицей Заявки
     */
    public function getRequests() {
        return $this->hasOne(Requests::className(), ['requests_id' => 'ratings_request_id']);
    }    

    /**
     * Метки для полей
     */
    public function attributeLabels()
    {
        return [
            'ratings_id' => 'Ratings ID',
            'ratings_request_id' => 'Ratings Request ID',
            'created_at' => 'Created At',
            'ratings_value' => 'Ratings Value',
            'user_id' => 'User ID',
        ];
    }
}
