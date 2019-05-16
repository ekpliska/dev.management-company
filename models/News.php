<?php

    namespace app\models;
    use Yii;
    use yii\db\Expression;
    use yii\db\ActiveRecord;
    use yii\behaviors\TimestampBehavior;
    use yii\behaviors\SluggableBehavior;
    use yii\imagine\Image;
    use Imagine\Image\Box;
    use rico\yii2images\behaviors\ImageBehave;
    use app\models\Rubrics;
    use app\models\Partners;
    use app\models\SendSubscribers;

    
/*
 * Новости
 * Рекламный блок (наличие переключатель isAdvert)
 */    
class News extends ActiveRecord
{
    
    const FOR_ALL = 'all';
    const FOR_CURRENT_HOUSE = 'house';
    
    const NOTICE_PERSONAL_OFFICE = 1;
    const NOTICE_EMAIL = 2;
    const NOTICE_PUSH = 3;
    
    const NOTICE_YES = 1;
    const NOTICE_NO = 0;
    
    public $files;
    
    public $isNotice;


    const SCENARIO_EDIT_NEWS = 'edit news';

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
                'class' => ImageBehave::className(),
            ],
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression("'" . date('Y-m-d H:i:s') . "'"),
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
                'news_title', 'news_text'], 'required'],
            
            [['news_type_rubric_id', 'news_status'], 'string'],
            
            [['news_text', 'news_text_mobile'], 'string', 'max' => 10000],
            
            [[
                'news_house_id', 
                'news_user_id', 
                'isPrivateOffice',
                'isEmail',
                'isPush'], 'integer'],
            
//            ['news_partner_id', 'default', 'value' => null],
            
            ['news_partner_id', 'required', 'when' => function($model) {
                if ($model->isAdvert) {
                    return 'Выберите котрагента';
                } else {
                    return $this->news_partner_id = null;
                }
            }],
            
            [['news_title', 'news_preview', 'slug'], 'string', 'max' => 255],
            
            ['slug', 'string'],
            
            [['files'], 'file', 
                'extensions' => 'doc, docx, pdf, xls, xlsx, ppt, pptx, txt, jpg, jpeg', 
                'maxFiles' => 4, 
                'maxSize' => 10 * 1024 * 1024,
            ],
            
            [['news_partner_id', 'isAdvert'], 'integer'],
            
            [['created_at', 'updated_at'], 'safe'],
                    
            ['isNotice', 'safe'],
            
        ];
    }
    
    /**
     * Связь с таблицей Рубрика (Тип публикации)
     */
    public function getRubric() {
        return $this->hasOne(Rubrics::className(), ['rubrics_id' => 'news_type_rubric_id']);
    }

    /**
     * Связь с таблицей Партнеры (Контрагенты)
     */
    public function getPartner() {
        return $this->hasOne(Partners::className(), ['partners_id' => 'news_partner_id']);
    }
    
    /*
     * Тип уведомления публикации
     */
    public static function getNoticeType() {
        return [
            self::NOTICE_PUSH => 'Push-оповещения',
            self::NOTICE_EMAIL => 'Email-оповещения',
        ];
    }
    
    /*
     * Статус размещения публикации
     */
    public static function getStatusPublish() {
        return [
            self::FOR_ALL => 'Для всех жильцов',
            self::FOR_CURRENT_HOUSE => 'Для конкретного дома',
        ];
    }
    
    /*
     * Получить превью публикации
     */
    public function getPreview() {
        if (empty($this->news_preview)) {
            return Yii::getAlias('@web') . 'images/not_found.png';
        }
        return Yii::getAlias('@web') . $this->news_preview;
    }

    /*
     * Найти публикацию по slug
     */
    public static function findNewsByID($news_id) {
        
        return self::find()
                ->where(['news_id' => $news_id])
                ->one();
    }
    
    /*
     * Найти публикацию по slug
     */
    public static function findNewsBySlug($slug) {
        
        return self::find()
                ->joinWith(['rubric', 'partner'])
                ->where(['slug' => $slug])
//                ->asArray()
                ->one();
        
    }

    /*
     * Формирование списка новостей для конечного пользователя
     * 
     * @param array $living_space
     *      $living_space['houses_id'] ID Дома
    */
    public static function getNewsByClients($living_space, $rubric = null) {
        
        if ($living_space == null) {
            return $news = [];
        }
        
        $news = self::find()
                ->select([
                    'news_id', 
                    'news_title', 'news_type_rubric_id', 
                    'news_partner_id', 'news_preview', 
                    'news_text', 
                    'created_at', 
                    'slug', 'rubrics_name', 'isAdvert', 'partners_name', 'partners_logo'])
                ->joinWith(['rubric', 'partner'])
                ->andWhere(['news_house_id' => $living_space['houses_id']])
                ->orWhere(['news_status' => 'all'])
                ->orderBy(['created_at' => SORT_DESC]);
        
        if ($rubric != null) {
            $news->andWhere(['news_type_rubric_id' => $rubric]);
        }
        
        return $news;
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
                
                $photo_path = Yii::getAlias('@webroot') . '/' . $dir . $file_name;
                $photo = Image::getImagine()->open($photo_path);
                $photo->thumbnail(new Box(900, 900))->save($photo_path, ['quality' => 90]);
                
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
        
        if ($files) {
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
        
        $preview = $this->news_preview;
        @unlink(Yii::getAlias('@webroot') . $preview);
        $this->getPathImageInText($this->news_text);
        
        // Проверяем существование директории
        if (file_exists($dir_news)) {
            $this->removeDirectory($dir_news);
        }
        
        // Удаление подписки на рассылку новости
        SendSubscribers::deleteSubscribe(SendSubscribers::POST_TYPE_NEWS, $this->news_id);
        
    }
    
    /*
     * После сохранения, проверяем если параметр Контрагент не установлен то поле очищаем, публикацию делаем как Новость
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        
        if (!$insert) {
            $this->news_text_mobile = strip_tags($this->news_text);
        }
        
        if (!isset($changedAttributes['isAdvert'])) {
            $this->news_partner_id = null;
            $this->isAdvert = false;
            return $this->save();
        }
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
            'news_text' => 'Текст публикации',
            'news_text_mobile' => 'Текст публикации (для мобильных устройств)',
            'news_preview' => 'Превью',
            'news_house_id' => 'Адрес',
            'news_user_id' => 'Пользователь',
            'isPrivateOffice' => 'Уведомления',
            'isSMS' => 'СМС',
            'isEmail' => 'Push-оповещения',
            'isPush' => 'Email-оповещения',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'files' => 'Прикрепленные документы',
            'isAdvert' => 'Рекламная публикация',
            'news_partner_id' => 'Контрагент',
        ];
    }

}
