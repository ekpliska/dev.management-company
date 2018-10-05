<?php

    namespace app\modules\managers\models\form;
    use Yii;
    use yii\base\Model;
    use yii\helpers\HtmlPurifier;
    use app\models\Voting;

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
    
    /*
     * Сохраняем запись публикации
     * 
     * @param string $file Превью новости
     * @param string $files Прикрепленные изображения
     */
    public function save($file) {
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            $add_voting = new Voting();
            $add_voting->voting_type = $this->type;
            $add_voting->voting_title = HtmlPurifier::process(strip_tags($this->title));
            $add_voting->voting_text = HtmlPurifier::process(strip_tags($this->text));
            $add_voting->voting_date_start = Yii::$app->formatter->asDatetime($this->date_start, 'php:Y-m-d H:i:s');
            $add_voting->voting_date_end = Yii::$app->formatter->asDatetime($this->date_end, 'php:Y-m-d H:i:s');
            $add_voting->voting_object = $this->object_vote;
            $add_voting->voting_user_id = Yii::$app->user->id;
            
            // Созраняем обложку
            $add_voting->uploadImage($file);
            
            if(!$add_voting->save()) {
                throw new \yii\db\Exception('Ошибка создания голосования. Ошибка: ' . join(', ', $add_voting->getFirstErrors()));
//                return ['error' => join(', ', $add_news->getFirstErrors())];
            }
            
            
            $transaction->commit();
            
            return $add_voting->id;
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        
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
