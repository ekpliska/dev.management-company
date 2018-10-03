<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\behaviors\TimestampBehavior;
    use yii\behaviors\SluggableBehavior;
    use rico\yii2images\behaviors\ImageBehave;
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
    
    public $files;


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
            
            'image' => [
//                'class' => 'rico\yii2images\behaviors\ImageBehave',
                'class' => ImageBehave::className(),
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
            
            [['files'], 'file', 
                'extensions' => 'doc, docx, pdf, xls, xlsx, ppt, pptx, txt', 
                'maxFiles' => 4, 
                'maxSize' => 256 * 1024,
            ],
            
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
     * Найти публикацию на slug
     */
    public static function findNewsBySlug($slug) {
        
        return self::find()
                ->where(['slug' => $slug])
                ->one();
        
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
    
    /*
     * Загрузка прикрепленные документов
     */
    public function uploadFiles($files) {
        
        if ($this->validate() && $files) {
            foreach ($files as $file) {
                $file_name = $file->basename;                
                $path = 'upload/store' . $file_name . '.' . $file->extension;
                // Получаем имя файла
                $file->saveAs($path);
                $this->attachImage($path, false, $file_name);
                @unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }
    
    
    /*
     * Парсит текст публикации, находит все используемые ссылки на изображения в тексте
     * Собирает в массив, и затем удаляет
     */
    public function getPathImageInText($text) {
        
        $pattern = '/<img(?:\\s[^<>]*?)?\\bsrc\\s*=\\s*(?|"([^"]*)"|\'([^\']*)\'|([^<>\'"\\s]*))[^<>]*>/i';
        
        if (preg_match_all($pattern, $text, $matches)) {
            foreach ($matches[1] as $image) {
                $path = parse_url($image)['path'];
                $path = substr($path, 4);
                @unlink (Yii::getAlias('@webroot') . $path);
            }
            return true;
        }
        return false;
    }
    
    /*
     * Метод удаления директории, содержащей прикрепленные документы к публикации
     */
    function removeDirectory($dir_news) {
        
        $objs = glob($dir_news . "/*");
        if ($objs) {
            foreach($objs as $obj) {
                is_dir($obj) ? removeDirectory($obj) : @unlink($obj);
            }
            rmdir($dir_news);
            return true;
        }
        return false;
    }
    
    /*
     * После запроса на удаление новости, удаляем изображение превью новости,
     * Вызываем метод на удаление всех изображений, используемых в тексте публикации
     * Вызываем метод на удаление директории с закрепленными за публикацией документами
     */
    public function afterDelete() {
        
        parent::afterDelete();
        
        // Формируем имя директории, где хранятся закрепленные за публикацией документы
        $dir_news = Yii::getAlias('@webroot') . '/upload/store/News/News' . $this->news_id;
        
        
        if ($this->getPathImageInText($this->news_text)) {
            // Проверяем существование директории
            if (file_exists($dir_news)) {
                $this->removeDirectory($dir_news);
            }
            $preview = $this->news_preview;
            @unlink(Yii::getAlias('@webroot') . $preview);
            
            return true;
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
            'files' => 'Прикрепленные документы',
        ];
    }

}
