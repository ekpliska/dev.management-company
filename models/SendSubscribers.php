<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

/**
 * Рассылка email уведомлений
 */
class SendSubscribers extends ActiveRecord {
    
    const POST_TYPE_NEWS = 'news';
    const POST_TYPE_VOTE = 'vote';
    
    /**
     * Талица БД
     */
    public static function tableName() {
        return 'send_subscribers';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['post_id', 'type_post'], 'required'],
            [['post_id', 'house_id', 'status_subscriber'], 'integer'],
            [['date_create'], 'safe'],
            [['type_post'], 'string', 'max' => 10],
        ];
    }
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'type_post' => 'Type Post',
            'house_id' => 'House ID',
            'status_subscriber' => 'Status Subscriber',
            'date_create' => 'Date Create',
        ];
    }
}
