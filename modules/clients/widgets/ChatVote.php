<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;

/*
 * Чат Голосования
 */    
class ChatVote extends Widget {

    public function init() {
        parent::init();
    }
    
    public function run() {
        
        return $this->render('chatvote/chat-vote');
        
    }
    
}
