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
    const TYPE_CHANGE_STATUS_IN_PAID_REQUEST = 'Change status in paid request';

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
            [['user_uid', 'type_notification', 'message'], 'required'],
            [['user_uid', 'created_at'], 'integer'],
            [['type_notification'], 'string', 'max' => 70],
            [['message', 'value_1', 'value_2', 'value_3'], 'string', 'max' => 255],
            [['user_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_uid' => 'user_id']],
        ];
    }
    
    /*
     * Формирование уведомления для Собственника
     * @param integer $user_id ID пользователя
     * @param string $type_notice Тип уведомления
     * @param string $request_num Номер заявки
     * @param string $value Статус заявки
     */
    public function createNoticeStatus($user_id, $type_notice, $request_num, $value) {
        
        if (empty($user_id) || empty($type_notice)) {
            return false;
        }
        
        $status_request = StatusRequest::statusName($value);
        
        $this->user_uid = 151;
        $this->type_notification = $type_notice;

        switch ($type_notice) {
            case self::TYPE_CHANGE_STATUS_IN_REQUEST:
                $message = "У заявки ID{$request_num} установлен статус {$status_request}";
                break;
            case self::TYPE_CHANGE_STATUS_IN_PAID_REQUEST:
                $url = null;
                break;
        }
        
        $this->message = $message;
        $this->value_1 = $request_num;
        
        return $this->save(false) ? true : false;
        
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
