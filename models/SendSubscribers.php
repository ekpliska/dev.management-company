<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\News;
    use app\models\Voting;

/**
 * Рассылка email уведомлений
 */
class SendSubscribers extends ActiveRecord {
    
    const POST_TYPE_NEWS = 'news';
    const POST_TYPE_VOTE = 'vote';
    
    const STATUS_SEND = 1;
    const STATUS_NOT_SEND = 0;
    
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
    
    /*
     * Создание записи на рассылку email уведомлений
     */
    public function createSubscriber($type_post, $post_id, $house_id = null) {
        
        $new_subscriber = new SendSubscribers();
        $new_subscriber->post_id = $post_id;
        $new_subscriber->type_post = $type_post;
        $new_subscriber->house_id = $house_id;
        $new_subscriber->status_subscriber = self::STATUS_NOT_SEND;
        
        $new_subscriber->save(false);
        
        return;
        
    }
    
    /*
     * Запуск рассылки
     */
    public function send() {
        
        // Массив электронных адресов для рассылки
        $subscribers = [];
        
        // Находим последную запись на очередь в рассыльщике со статусом "Рассылки не было"
        $subscriber_list = $this->find()
                ->where(['status_subscriber' => self::STATUS_NOT_SEND])
                ->orderBy(['date_create' => SORT_DESC])
                ->limit(1)
                ->one();
        
        
        // Если таких актуальной записи в рассыльщике нет, выходим
        if (!$subscriber_list) exit;
        
        
        // Если имеется запись со статусом "Рассылки не было"
        if ($subscriber_list->status_subscriber == self::STATUS_NOT_SEND) {
            
            
            // Получаем тип поста
            $type_post = $subscriber_list->type_post;
            // Получаем ID поста
            $last_post = $subscriber_list->post_id;
            // Получаем ID дома, на случай если пост был индивидаульным
            $house_id = $subscriber_list->house_id;
            
            // Информация о публикации
            $post_info = $this->getPostInfo($type_post, $last_post);
            
            if (!$post_info) exit;
            
            // Находим Собсвенников, которым рассылать уведомления
            $user_list = (new yii\db\Query())
                ->select('user_email as email')
                ->from('flats as f')
                ->join('LEFT JOIN', 'personal_account as pa', 'pa.personal_flat_id = f.flats_id')
                ->join('RIGHT JOIN', 'user as u', 'u.user_client_id = pa.personal_clients_id')
                ->where(['not', ['u.user_client_id' => null]]);
            
            if ($house_id) {
                $user_list->andWhere(['f.flats_house_id' => $house_id]);
            }
            
            $user_list = $user_list->groupBy('email')->all();
            
            // Формирует массив email адресов
            foreach ($user_list as $key => $email) {
                $subscribers[] = $email['email'];
            }
            
            /*
             * Отправляем всем подписчикам письма
             */
            foreach ($subscribers as $key => $subscriber) {
                
                $email = $subscriber;
                $params = [
                    'type_post' => $type_post,
                    'id_post' => $post_info->news_id ? $post_info->news_id : $post_info->voting_id,
                    'post_image' => $post_info->news_preview ? $post_info->news_preview : $post_info->voting_image,
                    'post_title' => $post_info->news_title ? $post_info->news_title : $post_info->voting_title,
                    'post_text' => $post_info->news_text ? substr(strip_tags($post_info->news_text), 0, 97) : substr(strip_tags($post_info->voting_text), 1, 97),
                    'post_slug' => $post_info->slug ? $post_info->slug : null
                ];
                
                $this->sendEmail($email, $params);
                
            }
            
            $subscriber_list->status_subscriber = self::STATUS_SEND;
            $subscriber_list->update();
            
        }
        
    }
    
    /*
     * Отправка письма получателю
     */
    private function sendEmail($email, $params) {
        
        $view_email = $params['type_post'] == self::POST_TYPE_NEWS ? 'NewsNotice' : 'VoteNotice';
        
        Yii::$app->mailer->compose("views/{$view_email}", ['params' => $params])
                ->setTo($email)
                ->setSubject($params['post_title'])
                ->send();
            
    }
    
    /*
     * Получить информацию о публикации
     */
    private function getPostInfo($type_post, $id_post) {
        
        switch ($type_post) {
            case self::POST_TYPE_NEWS: 
                $post_info = News::findNewsByID($id_post);
                break;
            case self::POST_TYPE_VOTE:
                $post_info = Voting::findVoteById($id_post);
            default:
                exit;
        }
        
        return $post_info;
        
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
