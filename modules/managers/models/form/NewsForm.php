<?php

    namespace app\modules\managers\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\Rubrics;
    use app\models\News;

/* 
 * Создание новой новости
 */
class NewsForm extends Model {
    
    // Статус публикации
    public $status;
    // Тип рубрикм
    public $rubric;
    // Заголовок
    public $title;
    // Текс новости
    public $text;
    // Изображение новости
    public $preview;
    // Дом
    public $house;
    // Тип публикации
    public $isPrivateOffice;
    // Тип оповещение (СМСб Email, Push)
    public $isNotice;
    
    public function rules() {
        return [
            [['rubric', 'title', 'text', 'preview', 'house', 'isPrivateOffice'], 'required'],
            
            ['title', 'string', 'min' => '10', 'max' => '255'],
            
            ['text', 'string', 'min' => '10', 'max' => '5000'],
            
            [['title', 'text'], 'filter', 'filter' => 'trim'],
            [['title', 'text'], 'match',
                'pattern' => '/^[А-Яа-яЁёA-Za-z0-9\_\-\@\.]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы русского и английского алфавита, цифры, знаки "-", "_"',
            ],
            
            [['status', 'house', 'isPrivateOffice', 'isNotice'], 'integer'],
            
        ];
    }
    
    public function attributeLabels() {
        return [
            'rubric' => 'Тип публикации',
            'title' => 'Заголовок публикации',
            'text' => 'Текст публикации',
            'preview' => 'Превью',
            'house' => 'Адрес',
            'isPrivateOffice' => 'Тип публикации',
            'isSMS' => 'СМС',
            'isEmail' => 'Email',
            'isPush' => 'Push',
        ];
    }
    
    
}
?>
