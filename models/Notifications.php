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
     * @param string $type_notice Тип уведомления
     * @param string $request_num Номер заявки
     */
    public static function createNoticeStatus($type_notice, $request_id, $status_name) {
                
        $status_request = StatusRequest::statusName($status_name);
        
        $notice = new Notifications();

        switch ($type_notice) {
            case self::TYPE_CHANGE_STATUS_IN_REQUEST:
                // ФОрмируем запрос по заявке
                $request_info = Requests::getFullInfoRequest($request_id);
                // Проверяем существование заявки
                if ($request_info == null) {
                    return false;
                }
                // Определяем статус заявки
                $notice->user_uid = $request_info->personalAccount->client->user->user_id;
                $notice->type_notification = $type_notice;
                $message = "У заявки ID{$request_info->requests_ident} установлен статус {$status_request}";
                $notice->value_1 = $request_info->requests_ident;
                break;
            case self::TYPE_CHANGE_STATUS_IN_PAID_REQUEST:
                $url = null;
                break;
        }
        
        $notice->message = $message;
        
        return $notice->save(false) ? true : false;
        
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
