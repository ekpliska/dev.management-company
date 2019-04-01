<?php

    namespace app\modules\managers\models\form;
    use yii\base\Model;

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
    
    
    
    public function attributeLabels() {
        
        return [
            'title' => 'Заголовок',
            'comments' => 'Комментарий',
        ];
        
    }
    
}
