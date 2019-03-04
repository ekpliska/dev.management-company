<?php

    namespace app\components\messagesBlock;
    use yii\helpers\ArrayHelper;
    use yii\base\Object;

class MessagesBlock extends Object {
    
    public  $array_message_admin = [
        'news-not-found' => 'Публикации еще не создавались.',
        'request-not-found' => 'Новых заявок нет.',
        'paid-request-not-found' => 'Новых заявок на платные услуги нет.'
    ];
    
    public function getAdministrationMessage($key) {
        
        return ArrayHelper::getValue($this->array_message_admin, $key);
        
    }
    
    
}
