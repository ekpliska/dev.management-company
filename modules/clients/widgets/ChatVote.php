<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\ChatToVote;
    use app\modules\clients\models\form\SendMessageForm;

/*
 * Чат Голосования
 */    
class ChatVote extends Widget {

    public $vote_id;
    
    public $_chat;

    public function init() {
        
        if ($this->vote_id == null) {
            throw new \yii\base\UnknownPropertyException('Ошибка передачи параметра');
        }
        
        $this->_chat = ChatToVote::getChat($this->vote_id);
        
        parent::init();
    }
    
    public function run() {
        
        $model = new SendMessageForm();
        
        return $this->render('chatvote/chat-vote', [
            'vote_id' => $this->vote_id,
            'chat_messages' => $this->_chat,
            'model' => $model,
        ]);
        
    }
    
}
