<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\User;

/**
 * Уведомления
 */
class Notifications extends ActiveRecord {
    
    const TYPE_NEW_MESSAGE_IN_REQUEST = 'New message in request';
    const TYPE_CHANGE_STATUS_IN_REQUEST = 'Change status in request';

    /**
     * Таблица  БД
     */
    public static function tableName() {
        return 'notifications';
    }
    
    /**
     * Связь с таблицей Пользователи
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'user_uid']);
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['user_uid', 'type_notification', 'message', 'value_1', 'value_2', 'value_3'], 'required'],
            [['user_uid', 'created_at'], 'integer'],
            [['type_notification'], 'string', 'max' => 70],
            [['message', 'value_1', 'value_2', 'value_3'], 'string', 'max' => 255],
            [['user_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_uid' => 'user_id']],
        ];
    }

    /**
     * Аттрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_uid' => 'User Uid',
            'type_notification' => 'Type Notification',
            'message' => 'Message',
            'value_1' => 'Value 1',
            'value_2' => 'Value 2',
            'value_3' => 'Value 3',
        ];
    }

}
