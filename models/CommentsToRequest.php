<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Requests;
    use yii\behaviors\TimestampBehavior;
    use app\models\Notifications;
    use app\models\TokenPushMobile;

/**
 * Комментарии к заявкам
 */
class CommentsToRequest extends ActiveRecord
{
    const SCENARIO_ADD_COMMENTS = 'add_comments_to_requests';
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    
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
            ['comments_text', 'required', 'on' => self::SCENARIO_ADD_COMMENTS],
            [['comments_request_id', 'comments_user_id', 'created_at'], 'integer'],
            [['comments_text'], 'string', 'max' => 1000, 'on' => self::SCENARIO_ADD_COMMENTS],
        ];
    }
    
    public function getComment() {
        return $this->hasOne(Requests::className(), ['requests_id' => 'comments_request_id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'comments_user_id']);
    }
    
    public function getRequest() {
        return $this->hasOne(Requests::className(), ['requests_id' => 'comments_request_id']);
    }

    /*
     * Жадная выгрузка данных для формирования комментариев, соответствующих своей заявке
     */
    public static function findCommentsById($request_id) {
        
        $comments = self::find()
                ->joinWith(['user'])
                ->andWhere(['comments_request_id' => $request_id])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
        
        return $comments;
    }
    
    public static function getCommentByRequest($request_id) {
        
        $query = (new \yii\db\Query)
                ->select('cr.created_at as date, cr.comments_text as text, '
                        . 'u.user_id as user, u.user_photo as photo')
                ->from('comments_to_request as cr')
                ->join('LEFT JOIN', 'user as u', 'u.user_id = cr.comments_user_id')
                ->where(['cr.comments_request_id' => $request_id])
                ->orderBy(['cr.created_at' => SORT_DESC])
                ->all();
        
        return $query;
    }
    
    /*
     * Сохранение комментария в бд
     */
    public function sendComment($request_id) {
        
        $user_name = User::getUserName();
        
        if ($this->validate()) {
            $this->comments_request_id = $request_id;
            $this->comments_user_id = Yii::$app->user->identity->id;
            $this->user_name = $user_name;
            
            if (Yii::$app->user->can('clients') || Yii::$app->user->can('clients_rent')) {
                // Формируем уведомление для диспетчера, который курирует заявку
                Notifications::createNoticeNewMessage(Notifications::TYPE_NEW_MESSAGE_IN_REQUEST, $request_id);
            }
            
            // Отправляем PUSH-уведомление
            if (Yii::$app->user->can('administrator') || Yii::$app->user->can('dispatcher') || Yii::$app->user->can('clients_rent')) {
                $user_id = Requests::find()->with('personalAccount.client.user')->where(['requests_id' => $request_id])->one();
                $push_note = TokenPushMobile::send($user_id->personalAccount->client->user->id, $user_name, $this->comments_text);
            }
            
            return $this->save() ? true : false;
        }
        return false;
    }
    
    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'comments_id' => 'Comments ID',
            'comments_request_id' => 'Comments Request ID',
            'comments_user_id' => 'Пользователь',
            'comments_text' => 'Ваш комментарий...',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата редактирования',
        ];
    }
}
