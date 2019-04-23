<?php

    namespace app\modules\clients\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\ChatToVote;
    use app\models\TokenPushMobile;
    
/**
 * Отправка сообщения в чат Опроса
 */
class SendMessageForm extends Model {
    
    public $message;
    
    public function rules() {
        
        return [
            [['message'], 'required', 'message' => 'Укажите ваше сообщение'],
            [['message'], 'filter', 'filter' => 'trim'],
            ['message', 'string', 'max' => 1000],
        ];
        
    }
    
    public function send($vote) {
        
        if (!$this->validate()) {
            return false;
        }
        $current_id = Yii::$app->user->id;
        
        $new_message = new ChatToVote();
        $new_message->vote_vid = $vote;
        $new_message->uid_user = $current_id;
        $new_message->chat_message = $this->message;
        
        // Отправляем push-уведомление
        $push_note = TokenPushMobile::sendPushToVote($current_id, $this->message, $vote);
        
        return $new_message->save() ? true : false;
        
    }
    
    public function attributeLabels() {
        
        return [
            'message' => 'Сообщение',
        ];
        
    }
    
}
