<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use app\models\News;
    use yii\validators\UrlValidator;
        
/**
 * Партнеры (Контрагенты)
 */
class Partners extends ActiveRecord
{
    
    public $image_logo;


    /**
     * Таблица в БД
     */
    public static function tableName()
    {
        return 'partners';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['partners_name'], 'required'],
            [['partners_name'], 'string', 'max' => 170],
            [[
                'partners_name', 
                'partners_adress', 
                'partners_site', 
                'partners_phone', 
                'partners_logo',
                'description', 'partners_email'], 'string', 'max' => 255],
            
            ['partners_site', UrlValidator::className(), 'message' => 'Не верный формат адреса'],
            
            ['partners_email', 'email'],
            
            [['image_logo'], 'file',
                'skipOnEmpty' => true,
                'extensions' => 'png, jpg',
                'maxSize' => 5 * 256 * 1024,
                'mimeTypes' => 'image/*',
            ],
            
        ];
        
        
    }
    
    /*
     * Связь с таблицей Новости
     */
    public function getNews() {
        return $this->hasMany(News::className(), ['news_partner_id' => 'partners_id']);
    }
    
    /*
     * Получить список всех партнеров
     */
    public static function getAllParnters() {
        
        $array = self::find()
                ->asArray()
                ->all();
        
        return ArrayHelper::map($array, 'partners_id', 'partners_name');
    }
    
    /*
     * Логотип партнера
     */
    public function getLogo() {
        
        if (empty($this->partners_logo)) {
            return Yii::getAlias('@web') . 'images/not_found.png';
        }
        
        return Yii::getAlias('@web') . '/web/' . $this->partners_logo;
        
    }
    
    public function upload() {
        
        $current_image = $this->partners_logo;
        
        if ($this->validate()) {
            if ($this->image_logo) {
                $dir = Yii::getAlias('@web') . 'upload/partners/';
                $file_name = 'partner_logo_' . time() . '.' . $this->image_logo->extension;
                $this->image_logo->saveAs($dir . $file_name);
                $this->image_logo = $file_name;
                $this->partners_logo = '/' . $dir . $file_name;
                @unlink(Yii::getAlias('@webroot' . $current_image));
            } else {
                $this->partners_logo = $current_image;
            }
                return $this->save() ? true : false;
        }
        
        return false;
        
    }

    /**
     * Атрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'partners_id' => 'Partners ID',
            'partners_name' => 'Наименование организации',
            'partners_adress' => 'Физический адрес',
            'partners_site' => 'Сайт',
            'partners_phone' => 'Контактный телефон',
            'partners_logo' => 'Логотип',
            'description' => 'Дополнительная служебная информация',
            'partners_email' => 'Электронный адрес',
        ];
    }
}
