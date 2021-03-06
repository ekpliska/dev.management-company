<?php

    namespace app\modules\api\v1\models\chat;
    use Yii;
    use yii\base\Model;
    use app\models\User;
    use app\models\CommentsToRequest;
    use app\models\ChatToVote;
    use app\models\StatusRequest;
    
/*
 * Чат
 */

class Chat extends Model {

    public $_user;

    public function __construct(User $user) {
        
        $this->_user = $user;
        parent::__construct();
    }
    
    /*
     * Сформировать список всех чатов
     */
    public function getChatList() {
        
        $request_chat = $this->getRequestChats();
        $vote_chat = $this->getVoteChats();
        $all_chats = array_merge((array)$request_chat, (array)$vote_chat);
        
        usort($all_chats, function ($a, $b) {
            if ($a['date_message'] == $b['date_message']) {
                return 0;
            }
            return ($a['date_message'] > $b['date_message']) ? +1 : -1;
        });
        
        return $all_chats;
        
    }
    
    /*
     * Получить все сообщения в текущем чате
     */
    public function getChatMessages($type, $chat_id) {
        
        $results = [];
        switch ($type) {
            case 'request':
                $messages = CommentsToRequest::find()
                    ->with('user', 'request')
                    ->where(['comments_request_id' => $chat_id])
                    ->orderBy(['created_at' => SORT_ASC])
                    ->all();
                foreach ($messages as $key => $message) {
                    $_message = [
                        'user_name' => $message['user_name'],
                        'photo' => $message['user']->photo,
                        'message' => $message['comments_text'],
                        'date_message' => $message['created_at'],
                        '_type' => ($message['comments_user_id'] == Yii::$app->user->getId()) ? 'out' : 'in',
                    ];
                    $results[] = $_message;
                }
                break;
            case 'vote':
                $messages = ChatToVote::find()
                    ->with('user', 'vote')
                    ->where(['vote_vid' => $chat_id])
                    ->orderBy(['created_at' => SORT_ASC])
                    ->all();
                foreach ($messages as $key => $message) {
                    $_message = [
                        'user_name' => $message['user']->client->clients_name,
                        'photo' => $message['user']->photo,
                        'message' => $message['chat_message'],
                        'date_message' => strtotime($message['created_at']),
                        '_type' => ($message['uid_user'] == Yii::$app->user->getId()) ? 'out' : 'in',
                    ];
                    $results[] = $_message;
                }
                break;
            default:
                return false;
        }
        
        return $results;
        
    }
    
    /*
     * Список чатов по Заявкам
     */
    private function getRequestChats() {
        
        $result = [];
        
        $chat_requests = CommentsToRequest::find()
                ->select(['comments_request_id'])
                ->where(['comments_user_id' => Yii::$app->user->getId()])
                ->groupBy('comments_request_id')
                ->asArray()
                ->all();
        
        if (!$chat_requests) {
            $result = null;
        }
        
        foreach ($chat_requests as $key => $chat_request) {
            $_chat = CommentsToRequest::find()
                    ->with('user', 'request')
                    ->where(['comments_request_id' => $chat_request['comments_request_id']])
                    ->orderBy(['created_at' => SORT_DESC])
                    ->one();
            
            $_message = [
                'id_chat' => $_chat['comments_request_id'],
                'chat_title' => $_chat['request']->typeRequest->type_requests_name,
                'user_name' => $_chat['user_name'],
                'user_photo' => $_chat['user']->user_photo,
                'message' => $_chat['comments_text'],
                'date_message' => $_chat['created_at'],
                '_type' =>  'request',
            ];
            
            $result[] = $_message;
        }
            
        return $result ? $result : $result = null;
    }
    
    /*
     * Список чатов по опросам
     */
    private function getVoteChats() {
        
        $result = [];
        
        $chat_votes = ChatToVote::find()
                ->select(['vote_vid'])
                ->where(['uid_user' => Yii::$app->user->getId()])
                ->groupBy('vote_vid')
                ->asArray()
                ->all();
        
        if (!$chat_votes) {
            $result = null;
        }
        foreach ($chat_votes as $key => $chat_request) {
            $_chat = ChatToVote::find()
                    ->with('user', 'vote')
                    ->where(['vote_vid' => $chat_request['vote_vid']])
                    ->orderBy(['created_at' => SORT_DESC])
                    ->one();
            
            $_message = [
                'id_chat' => $_chat['vote_vid'],
                'chat_title' => $_chat['vote']->voting_title,
                'user_name' => $_chat['user']->client->clients_name,
                'user_photo' => $_chat['user']->user_photo,
                'message' => $_chat['chat_message'],
                'date_message' => strtotime($_chat['created_at']),
                '_type' => 'vote',
            ];
            
            $result[] = $_message;
        }
            
        return $result;
        
    }
}
