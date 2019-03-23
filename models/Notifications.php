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
    const TYPE_HAVE_MISSED_REQUEST = 'Have the missed requests';
    const TYPE_HAVE_MISSED_PAID_REQUEST = 'Have the missed paid requests';

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
     * Поиск уведомления по его ID и ID текущего пользователя
     */
    public static function findOneNotice($notice_id) {
        
        $notice = self::find()
                ->where(['id' => $notice_id, 'user_uid' => Yii::$app->user->id])
                ->one();
        
        return $notice->delete() ? true : false;
        
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
                // Формируем запрос по заявке
                $request_info = Requests::getFullInfoRequest($request_id);
                // Проверяем существование заявки
                if ($request_info == null) {
                    return false;
                }
                // Определяем статус заявки
                $notice->user_uid = $request_info->personalAccount->client->user->user_id;
                $notice->type_notification = $type_notice;
                $message = "Заявка ID{$request_info->requests_ident} установлен статус {$status_request}";
                $notice->value_1 = $request_info->requests_ident;
                break;
            case self::TYPE_CHANGE_STATUS_IN_PAID_REQUEST:
                // Формируем запрос по заявке
                $paid_request_info = PaidServices::getFullInfoPaidRequest($request_id);
                // Проверяем существование заявки
                if ($paid_request_info == null) {
                    return false;
                }
                // Определяем статус заявки
                $notice->user_uid = $paid_request_info->personalAccount->client->user->user_id;
                $notice->type_notification = $type_notice;
                $message = "Платная услуга, заявка ID{$paid_request_info->services_number} установлен статус {$status_request}";
                break;
        }
        
        $notice->message = $message;
        
        return $notice->save(false) ? true : false;
        
    }
    
    /*
     * Формирование уведомления Диспетчеру
     */
    public static function createNoticeNewMessage($type_notice, $request_id) {
        
        $request_body = Requests::findOne(['requests_id' => $request_id]);
        $user = User::findByEmployeeId($request_body->requests_dispatcher_id);
        
        if ($request_body->requests_dispatcher_id != null) {
            $notice = new Notifications();

            $notice->user_uid = $user->user_id;
            $notice->type_notification = $type_notice;
            $notice->message = "У заявки ID{$request_body->requests_ident} новое сообщение от собственника";
            $notice->value_1 = $request_body->requests_ident;

            return $notice->save(false) ? true : false;
        }
        
        return false;
        
    }
    
    public static function createNoticeMisses($type_notice, $request_list) {
        
        switch ($type_notice) {
            case self::TYPE_HAVE_MISSED_REQUEST:
                foreach ($request_list as $request) {
                    $check_notice = self::find()->where(['value_1' => $request, 'type_notification' => $type_notice])->one();
                    if (empty($check_notice)) {
                        $notice = new Notifications();
                        $notice->user_uid = Yii::$app->user->id;
                        $notice->type_notification = $type_notice;
                        $notice->message = "Заявка ID{$request}, истекло время рассмотрения";
                        $notice->value_1 = $request;
                        $notice->save(false);
                    }
                }
                break;
            case self::TYPE_HAVE_MISSED_PAID_REQUEST:
                foreach ($request_list as $paid_request) {
                    $check_notice = self::find()->where(['value_1' => $paid_request, 'type_notification' => $type_notice])->one();
                    if (empty($check_notice)) {
                        $notice = new Notifications();
                        $notice->user_uid = Yii::$app->user->id;
                        $notice->type_notification = $type_notice;
                        $notice->message = "Платная услуга, заявка ID{$paid_request}, истекло время рассмотрения";
                        $notice->value_1 = $paid_request;
                        $notice->save(false);
                    }
                }
                break;
        }
        
        return true;
        
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
