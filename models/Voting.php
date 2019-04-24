<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\web\UploadedFile;
    use yii\imagine\Image;
    use Imagine\Image\Box;
    use app\models\Questions;
    use app\models\RegistrationInVoting;
    use app\models\TokenPushMobile;
    use app\models\Notifications;

/*
 * Голосование
 */    
class Voting extends ActiveRecord
{
    
    // Активно
    const STATUS_ACTIVE = 0;
    // Завершено
    const STATUS_CLOSED = 1;
    // Отменено
    const STATUS_CANCEL = 2;

    const TYPE_FOR_ALL = 'all';
    const TYPE_FOR_HOUSE = 'house';
    
    public $imageFile;

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
            
            [['voting_house_id', 'status', 'voting_user_id'], 'integer'],
            
            ['voting_type', 'string'],
            
            ['voting_title', 'string', 'max' => 255],
            ['voting_text', 'string', 'max' => 1000],
            
            [['voting_title', 'voting_text'], 'filter', 'filter' => 'trim'],
            
            [['voting_date_start', 'voting_date_end', 'created_at', 'updated_at'], 'safe'],
            
            ['voting_date_start', 'validateStartDateVote'],
                    
            ['voting_image', 'string', 'max' => 255],
            
            ['voting_user_id', 'default', 'value' => Yii::$app->user->identity->id],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            
            ['imageFile', 'safe'],
            
            [['imageFile'], 'file',
                'skipOnEmpty' => true,
                'extensions' => 'png, jpg',
                'maxSize' => 256 * 1024 * 1024,
                'mimeTypes' => 'image/*',
            ],
            
        ];
    }
    
    /**
     * Свзяь с таблицей Вопросы
     */
    public function getQuestion() {
        return $this->hasMany(Questions::className(), ['questions_voting_id' => 'voting_id']);
    }
    
    /**
     * Свзяь с таблицей Регистрация в голосовании
     */
    public function getRegistration() {
        return $this->hasMany(RegistrationInVoting::className(), ['voting_id' => 'voting_id']);
    }

    /**
     * Свзяь с таблицей Регистрация в голосовании
     */
    public function getParticipant() {
        return $this->hasMany(RegistrationInVoting::className(), ['voting_id' => 'voting_id'])
                ->with('user')
                ->where(['status' => RegistrationInVoting::STATUS_ENABLED]);
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
            self::TYPE_FOR_ALL => 'Для всех',
            self::TYPE_FOR_HOUSE =>'Для конкретного дома',
        ];
    }
    
    /*
     * Список статусов голосования
     */
    public function getStatusVoting() {
        return [
            self::STATUS_ACTIVE => 'Активно',
            self::STATUS_CLOSED => 'Завершено',
            self::STATUS_CANCEL => 'Отменено',
        ];
    }
    
    /*
     * Получить список всех голосований
     */
    public static function findAllVoting() {
        
        return self::find()
                ->joinWith('registration')
                ->orderBy(['voting_date_start' => SORT_DESC])
                ->asArray();
        
    }
    
    /*
     * Получить список всех голосований для конечного пользователя
     */
    public static function findAllVotingForClient($house_id) {
        
        $votings = self::find()
                ->joinWith('registration')
                ->where(['voting_type' => 'all'])
                ->orWhere(['voting_house_id' => $house_id])
                ->groupBy(['voting_id'])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->all();
        
        return $votings;
    }
    
    /*
     * Найти голосование по ID
     */
    public static function findVotingById($voting_id) {
        
        $result = self::find()
                ->joinWith(['question', 'question.answer'])
                ->andWhere(['voting_id' => $voting_id])
                ->asArray()
                ->one();
        
        return $result;
    }
    
    /*
     * Получить ID голосования
     */
    public function getId() {
        return $this->voting_id;
    }
    
    public function getImage() {
        if (empty($this->voting_image)) {
            return Yii::getAlias('@web') . '/images/not_found.png';
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
//    public function uploadImage($file) {
//        
//        $current_image = $this->voting_image;
//        
//        if ($this->validate()) {
//            if ($file) {
//                $this->voting_image = $file;
//                $dir = Yii::getAlias('upload/voting/cover/');
//                $file_name = 'previews_voting_' . time() . '.' . $this->voting_image->extension;
//                $this->voting_image->saveAs($dir . $file_name);
//                $this->voting_image = '/' . $dir . $file_name;
//                @unlink(Yii::getAlias('@webroot' . $current_image));
//            } else {
//                $this->voting_image = $current_image;
//            }
//            return $this->save() ? true : false;
//        }
//        
//        return false;
//    }
    
    public function beforeSave($insert) {
        
        $current_image = $this->voting_image;
        
        $file = UploadedFile::getInstance($this, 'imageFile');
        
        if ($file) {
            
            $this->voting_image = $file;
            $dir = Yii::getAlias('upload/voting/cover/');
            $file_name = 'previews_voting_' . time() . '.' . $this->voting_image->extension;
            $this->voting_image->saveAs($dir . $file_name);
            $this->voting_image = '/' . $dir . $file_name;
            
            $photo_path = Yii::getAlias('@webroot') . '/' . $dir . $file_name;
            $photo = Image::getImagine()->open($photo_path);
            $photo->thumbnail(new Box(900, 900))->save($photo_path, ['quality' => 90]);
            
            @unlink(Yii::getAlias('@webroot' . $current_image));
            
        }
        
        return parent::beforeSave($insert);
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
    
    /*
     * Закрытие опроса
     */
    public function closeVoting() {
        
        $this->status = self::STATUS_CLOSED;
        return $this->save(false) ? true : false;
        
    }
    
    /*
     * После создания опроса, отправляем push-уведомление
     * Создаем оповещени
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $house_id = $this->voting_house_id;
            TokenPushMobile::sendPublishNotice(TokenPushMobile::TYPE_PUBLISH_VOTE, $this->voting_title, $house_id);
        }
    }
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'voting_id' => 'Voting ID',
            'voting_type' => 'Тип голосования',
            'voting_title' => 'Заголовок голосования',
            'voting_text' => 'Описание голосования',
            'voting_date_start' => 'Дата начала голосования',
            'voting_date_end' => 'Дата окончания голосования',
            'voting_house_id' => 'Адресс',
            'voting_image' => 'Обложка',
            'status' => 'Статус',
            'created_at' => 'Дата создания голосования',
            'updated_at' => 'Дата обновления голосования',
            'voting_user_id' => 'Прользователь',
        ];
    }

}
