<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\behaviors\TimestampBehavior;
    use yii\behaviors\SluggableBehavior;
    use app\models\Rubrics;

class News extends ActiveRecord
{
    
    const ONLY_PERSONAL_OFFICE = 0;
    const ONLY_PERSONAL_OFFICE_WITH_NOTICE = 1;
    
    const FOR_ALL = 0;
    const FOR_ALL_HOUSE_AREA = 1;
    const FOR_CURRENT_HOUSE = 2;
    
    const NOTICE_SMS = 1;
    const NOTICE_EMAIL = 2;
    const NOTICE_PUSH = 3;

    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'news';
    }

    public function behaviors () {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'news_title',
            ],
            [
                'class' => TimestampBehavior::className(),
//                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ],
        ];
    }    
    
    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [[
                'news_type_rubric_id', 
                'news_title', 'news_text', 
                'news_user_id', 
                'isPrivateOffice'], 'required'],
            
            ['news_title', 'unique',
                'targetClass' => self::className(),
                'targetAttribute' => 'news_title',
                'message' => 'Заголовок публикации не явяляется уникальным'],
            
            [[
                'news_type_rubric_id', 'news_house_id', 
                'news_user_id', 
                'isPrivateOffice', 
                'news_status', 
                ], 'integer'],
            
            [['isSMS', 'isEmail', 'isPush'], 'boolean'],
            
            [['news_text'], 'string'],
            [['news_title', 'news_preview', 'slug'], 'string', 'max' => 255],
            
            ['slug', 'string'],
            
        ];
    }
    
    /**
     * Связь с таблицей Рубрика (Тип публикации)
     */
    public function getRubric() {
        return $this->hasOne(Rubrics::className(), ['rubrics_id' => 'news_type_rubric_id']);
    }
    
    /*
     * Тип уведомления публикации
     */
    public static function getArrayStatusNotice() {
        return [
            self::ONLY_PERSONAL_OFFICE => 'Публикация только в личном кабинете',
            self::ONLY_PERSONAL_OFFICE_WITH_NOTICE => 'Публикация в личном кабинете с оповещением',
        ];
    }
    
    /*
     * Статус размещения публикации
     */
    public static function getStatusPublish() {
        return [
            self::FOR_ALL => 'Для всех жильцов',
            self::FOR_ALL_HOUSE_AREA => 'Для жилого комплекса',
            self::FOR_CURRENT_HOUSE => 'Для конкретного дома',
        ];
    }
    
    /*
     * Тип оповещения
     */
    public static function getNoticeType() {
        return [
            self::NOTICE_SMS => 'СМС',
            self::NOTICE_EMAIL => 'Email',
            self::NOTICE_PUSH => 'Push',
        ];
    }
    
    /*
     * Получить превью публикации
     */
    public function getPreview() {
        if (empty($this->news_preview)) {
            return Yii::getAlias('@web') . 'images/news_preview';
        }
        return Yii::getAlias('@web') . $this->news_preview;
    }
    
    /*
     * Загрузка изображения услуги
     */    
    public function uploadImage($file) {
        
        $current_image = $this->news_preview;
        
        if ($this->validate()) {
            if ($file) {
                $this->news_preview = $file;
                $dir = Yii::getAlias('upload/news/previews/');
                $file_name = 'previews_news_' . time() . '.' . $this->news_preview->extension;
                $this->news_preview->saveAs($dir . $file_name);
                $this->news_preview = '/' . $dir . $file_name;
                @unlink(Yii::getAlias('@webroot' . $current_image));
            } else {
                $this->news_preview = $current_image;
            }
            return $this->save() ? true : false;
        }
        
        return false;
    }   
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'news_id' => 'News ID',
            'news_type_rubric_id' => 'Тип публикации',
            'news_title' => 'Заголовок публикации',
            'news_text' => 'Текс публикации',
            'news_preview' => 'Превью',
            'news_house_id' => 'Адрес',
            'news_user_id' => 'Пользователь',
            'isPrivateOffice' => 'Уведомления',
            'isSMS' => 'СМС',
            'isEmail' => 'Email',
            'isPush' => 'Push',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

}
