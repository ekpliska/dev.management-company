<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use app\models\Questions;

class Voting extends ActiveRecord
{
    
    // Активено
    const STATUS_ACTIVE = 0;
    // Завершено
    const STATUS_CLOSED = 1;
    // Отменено
    const STATUS_CANCEL = 2;

    const TYPE_ALL_HOUSE = 0;
    const TYPE_PORCH = 1;    
    
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'voting';
    }
    
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression("'" . date('Y-m-d H:i:s') . "'"),
            ]
        ];
    }    

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [[
                'voting_type', 
                'voting_title', 'voting_text', 
//                'voting_image', 
                'voting_date_start', 'voting_date_end'], 'required'],
            
            [['voting_type', 'voting_house', 'voting_porch', 'status', 'voting_user_id'], 'integer'],
            [['voting_text'], 'string'],
            
            [['voting_date_start', 'voting_date_end', 'created_at', 'updated_at'], 'safe'],
            
            ['voting_date_start', 'validateStartDateVote'],
                    
            [['voting_title', 'voting_image'], 'string', 'max' => 255],
            
            ['voting_user_id', 'default', 'value' => Yii::$app->user->identity->id],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            
            [['voting_image'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['voting_image'], 'image', 'maxWidth' => 510, 'maxHeight' => 510],
            
        ];
    }
    
    /**
     * Свзяь с таблицей ВОпросы
     */
    public function getQuestion() {
        return $this->hasMany(Questions::className(), ['questions_voting_id' => 'voting_id']);
    }
    
    /*
     * Проверка даты начала и даты завершения голосования
     */
    public function validateStartDateVote() {
        
        if (strtotime($this->voting_date_start) > strtotime($this->voting_date_end)) {
            return $this->addError('voting_date_start', 'Дата начала голосования не может быть позже даты завершения голосования');
        }
    }

    /*
     * Тип голосования
     */
    public static function getTypeVoting() {
        return [
            self::TYPE_ALL_HOUSE => 'Для дома',
            self::TYPE_PORCH => 'Подьезд',
        ];
    }
    
    public function getStatusVoting() {
        return [
            self::STATUS_ACTIVE => 'Активно',
            self::STATUS_CLOSED => 'Закрыто',
            self::STATUS_CANCEL => 'Отменено',
        ];
    }
    
    /*
     * 
     */
    public static function findAllVoting() {
        
        return self::find()
                ->orderBy(['voting_date_start' => SORT_DESC])
                ->asArray();
        
    }
    
    /*
     * Получить ID голосования
     */
    public function getId() {
        return $this->voting_id;
    }
    
    public function getImage() {
        if (empty($this->voting_image)) {
            return Yii::getAlias('@web' . '/images/not_found.png');
        }
        return Yii::getAlias('@web') . $this->voting_image;
    }
    
    /*
     * Поиск записи по ID голосования
     */
    public static function findByID($voting_id) {
        return self::find()
                ->where(['voting_id' => $voting_id])
                ->one();
    }
    
    /*
     * Загрузка обложки голосования
     */
    public function uploadImage($file) {
        
        $current_image = $this->voting_image;
        
        if ($this->validate()) {
            if ($file) {
                $this->voting_image = $file;
                $dir = Yii::getAlias('upload/voting/cover/');
                $file_name = 'previews_voting_' . time() . '.' . $this->voting_image->extension;
                $this->voting_image->saveAs($dir . $file_name);
                $this->voting_image = '/' . $dir . $file_name;
                @unlink(Yii::getAlias('@webroot' . $current_image));
            } else {
                $this->voting_image = $current_image;
            }
            return $this->save() ? true : false;
        }
        
        return false;
    }
    
    /*
     * После запроса на удаление голосования, удаляем изображение обложку голосования,
     * удаляем вс вопросы, закрепленные за голосованием
     */
    public function afterDelete() {
        
        parent::afterDelete();
        
        $cover = $this->voting_image;
        @unlink(Yii::getAlias('@webroot') . $cover);
    }    
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'voting_id' => 'Voting ID',
            'voting_type' => 'Тип голосования',
            'voting_title' => 'Заголвок голосования',
            'voting_text' => 'Описание голосования',
            'voting_date_start' => 'Дата начала голосования',
            'voting_date_end' => 'Дата окончания голосования',
            'voting_house' => 'Voting House',
            'voting_porch' => 'Voting Porch',
            'voting_image' => 'Обложка',
            'status' => 'Статус',
            'created_at' => 'Дата создания голосования',
            'updated_at' => 'Дата обновления голосования',
            'voting_user_id' => 'Прользователь',
        ];
    }

}
