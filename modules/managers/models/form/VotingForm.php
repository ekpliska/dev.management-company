<?php

    namespace app\modules\managers\models\form;
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
    public $adress;
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
            
            [['type', 'adress'], 'integer'],
            
            ['title', 'string', 'min' => 6, 'max' => 255],
            ['text', 'string', 'min' => 6, 'max' => 5000],
            
            [['date_start', 'date_end'], ''],
            
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['image'], 'image', 'maxWidth' => 510, 'maxHeight' => 510],
        ];
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
            'adress' => 'Адрес',
            'image' => 'Оброжка',
        ];
    }
    
}
