<?php

    namespace app\modules\managers\models\form;
    use yii\base\Model;
    use app\models\CommentsToCounters;

/**
 * Добавление Комментария 
 * на сранице "Показания приборов учета" в личном кабинете "Собсвенника"
 */
class CommentToCounterForm extends Model {
    
    public $title;
    public $comments;
    
    public function rules() {
        return [
            [['title', 'comments'], 'required', 'message' => 'Поле обязательно для заполнения'],
            ['title', 'string', 'max' => 255],
            ['comments', 'string', 'max' => 1000],
        ];
    }
    
    public function save($account_id) {
        
        if (!$this->validate()) {
            return false;
        }
        
        $notification = new CommentsToCounters();
        $notification->comments_title = $this->title;
        $notification->comments_text = $this->comments;
        $notification->account_id = $account_id;
        
        return $notification->save() ? true : false;
        
    }
    
    public function attributeLabels() {
        
        return [
            'title' => 'Заголовок',
            'comments' => 'Комментарий',
        ];
        
    }
    
}
