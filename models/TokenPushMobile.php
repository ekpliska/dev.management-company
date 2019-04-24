<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\components\firebasePush\FirebaseNotifications;
    use yii\helpers\ArrayHelper;
    use app\models\RegistrationInVoting;
    use app\models\User;

/**
 * Токены мобильных устройств для рассылки PUSH-уведомлений
 */
class TokenPushMobile extends ActiveRecord {
    
    const TYPE_PUBLISH_NEWS = 'news';
    const TYPE_PUBLISH_VOTE = 'vote';
    
    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'token_push_mobile';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['user_uid', 'token'], 'required'],
            [['user_uid'], 'integer'],
            [['token'], 'string', 'max' => 255],
            [['token'], 'unique'],
            [['user_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_uid' => 'user_id']],
        ];
    }
    
    /**
     * Связь с таблицей 
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'user_uid']);
    }
    
    /*
     * Установка токена мобильного устройства
     * {"push_token":"key"}
     */
    public function setPushToken($_token) {
        
        // Проверяем наличие токена
        $push_token = TokenPushMobile::findOne(['token' => $_token]);
        
        // Если токена не существует то добавляем его в базу
        if (!$push_token) {
            $new_token = new TokenPushMobile();
            $new_token->user_uid = Yii::$app->user->id;
            $new_token->token = $_token;
            return $new_token->save(false) ? true : false;
        } // Если токен сущствует, то проверяем его принадлежность текущему пользователю
        elseif ($push_token && $push_token->user_uid != Yii::$app->getUser()->id) { 
            $push_token->user_uid = Yii::$app->user->id;
            return $push_token->save(false) ? true : false;
        }
        
        return true;
        
    }
    
    /*
     * Отправка уведомления
     */
    public static function send($user_id, $title = null, $message) {
        
        
        $_tokens = self::find()
                ->where(['user_uid' => $user_id])
                ->asArray()
                ->all();
        // Если массив токенов не пустой, то отправляем push-уведомления
        if (!empty($_tokens)) {
            $tokens = ArrayHelper::getColumn($_tokens, 'token');
            $message = [
                'title' => "{$title}",
                'body' => $message
            ];

            $notes = new FirebaseNotifications();
            return $notes->sendNotification($tokens, $message);            
        }
        
        return true;
    }
    
    /*
     * Отправка уведомлений из чата Опроса
     */
    public function sendPushToVote($from, $message_text, $vote_id) {
        
        // От кого сообщение
        $user_from = User::find()->joinWith('client')->where(['user_id' => $from])->one();
        
        // Рассылка всем кто зарегистрирован на голосование
        $participants = RegistrationInVoting::find()
                ->joinWith('pushToken')
                ->where(['voting_id' => $vote_id, 'status' => RegistrationInVoting::STATUS_ENABLED])
                ->andWhere(['!=', 'user_id', $from])
                ->asArray()
                ->all();
        
        $tokens = [];
        foreach ($participants as $key => $participant) {
            foreach ($participant['pushToken'] as $key => $token) {
                $tokens[] .= $token['token'];
            }
        }
        $message = [
            'title' => "{$user_from->client->clients_name}",
            'body' => $message_text
        ];
            
        if ($tokens) {
            $notes = new FirebaseNotifications();
            return $notes->sendNotification($tokens, $message);
        }
        
        return true;
        
    }
    
    /*
     * Отправка уведомлений для Новостей, Опроса
     * @param string $for_whom Для всех, Для конкреного дома
     * @param string $type Новости, Опрос
     */
    public static function sendPublishNotice($type, $message_text, $house_id = null) {
        
        if ($house_id == null) {
            // Собираем все токены для рассылки
            $_tokens = self::find()
                ->asArray()
                ->all();
            $tokens = ArrayHelper::getColumn($_tokens, 'token');
        } elseif ($house_id) {
            // Собираем токены для собсвенников указанного дома
            $_tokens = (new yii\db\Query())
                ->select('token')
                ->from('flats as f')
                ->join('LEFT JOIN', 'personal_account as pa', 'pa.personal_flat_id = f.flats_id')
                ->join('LEFT JOIN', 'account_to_users as ua', 'ua.account_id = pa.account_id')
                ->join('RIGHT JOIN', 'token_push_mobile as t', 't.user_uid = ua.user_id')
                ->where(['flats_house_id' => $house_id])
                ->groupBy('token')
                ->all();
            $tokens = ArrayHelper::getColumn($_tokens, 'token');
        }
        $message = [
            'title' => $type == 'news' ? "Новости" : "Опрос",
            'body' => $message_text
        ];
        if ($tokens) {
            $notes = new FirebaseNotifications();
            return $notes->sendNotification($tokens, $message);
        }
        return true;            
        
    }
    
    /**
     * Атрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_uid' => 'User Uid',
            'token' => 'Token',
        ];
    }

}
