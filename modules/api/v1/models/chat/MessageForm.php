<?php

    namespace app\modules\api\v1\models\chat;
    use Yii;
    use yii\base\Model;
    use app\models\CommentsToRequest;
    use app\models\ChatToVote;
    use app\models\User;
    use app\models\Requests;
    use app\models\Voting;
    use app\models\Notifications;

/**
 * Отправка сообщения
 */
class MessageForm extends Model {
    
    public $chat_id;
    public $type_chat;
    public $message;
    
    public function rules() {
        return [
            [['message', 'chat_id', 'type_chat'], 'required', 'message' => 'Поле обязательно для заполнения'],
            ['message', 'string', 'max' => 1000],
            ['message', 'filter', 'filter' => 'trim'],
        ];
    }
    
    public function send() {
        
        if (!$this->validate()) {
            return false;
        }
        
        switch ($this->type_chat) {
            case 'request':
                if (!Requests::findOne(['requests_id' => $this->chat_id])) {
                    return false;
                }
                $new_messaqe = new CommentsToRequest();
                $new_messaqe->comments_request_id = $this->chat_id;
                $new_messaqe->comments_text = $this->message;
                $new_messaqe->comments_user_id = Yii::$app->user->getId();
                $new_messaqe->user_name = User::getUserName();
                // Оправляем уведомления Диспетчеру
                Notifications::createNoticeNewMessage(Notifications::TYPE_NEW_MESSAGE_IN_REQUEST, $this->chat_id);
                break;
            case 'vote':
                if (!Voting::findOne(['voting_id' => $this->chat_id])) {
                    return false;
                }
                $new_messaqe = new ChatToVote();
                $new_messaqe->vote_vid = $this->chat_id;
                $new_messaqe->uid_user = Yii::$app->user->getId();
                $new_messaqe->chat_message = $this->message;
                break;
            default:
                return false;
        }
        
        return $new_messaqe->save() ? true : false;
        
    }
    
}
