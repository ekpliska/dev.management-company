<?php

    namespace app\modules\managers\models\form;
    use Yii;
    use yii\base\Model;

/**
 * Создание голосования
 */
class VotingForm extends Model {
    
    public $type;
    public $title;
    public $text;
    public $date_start;
    public $date_end;
    public $object_vote;
    public $image;

    /*
     * Правила валидации
     */
    public function rules() {
        return [
            [[
                'type', 
                'title', 'text', 
                'date_start', 'date_end', 
                'adress', 
                'image'], 'required'],
            
            [['type', 'object_vote'], 'integer'],
            
            ['title', 'string', 'min' => 6, 'max' => 255],
            ['text', 'string', 'min' => 6, 'max' => 5000],
            
            [['date_start', 'date_end'], 'date', 'format' => 'php:dd.mm.yyyy hh:ii'],
            
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['image'], 'image', 'maxWidth' => 510, 'maxHeight' => 510],
        ];
    }
    
    public function save($file) {
        //
    }
    
    /*
     * Атрибуты полей
     */
    public function attributeLabels() {
        return [
            'type' => 'Тип голосования',
            'title' => 'Заголовок голосования',
            'text' => 'Описание голосования',
            'date_start' => 'Дата начала голосования',
            'date_end' => 'Дата окончания голосования',
            'object_vote' => 'Для кого голосование',
            'image' => 'Оброжка',
        ];
    }
    
}
