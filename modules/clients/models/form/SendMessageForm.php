<?php

    namespace app\modules\clients\models\form;
    use yii\base\Model;

/**
 * Отправка сообщения в чат Опроса
 */
class SendMessageForm extends Model {
    
    public $message;
    
    public function rules() {
        
        return [
            [['message'], 'required'],
            ['message', 'string', 'max' => 1000],
        ];
        
    }
    
    public function send($vote_id) {
        
        return true;
        
    }
    
    public function attributeLabels() {
        
        return [
            'message' => 'Сообщение',
        ];
        
    }
    
}
