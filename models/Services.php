<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Units;
    use app\models\CategoryServices;
    use yii\helpers\ArrayHelper;

/**
 * Услуги
 */
class Services extends ActiveRecord
{
    
    const TYPE_SERVICE = 0;
    const TYPE_PAY = 1;
    
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            
            [[
                'service_category_id',
                'service_name',
                'service_unit_id',
                'service_price',
                'service_description',
                'service_image'], 'required'],
            
            [['service_category_id', 'service_unit_id'], 'integer'],
            
            ['service_price', 'number', 'numberPattern' => '/^\d+(.\d{1,2})?$/'],
            
            [['service_name', 'service_image'], 'string', 'min' => '10', 'max' => 255],
            
            ['services_description', 'string', 'min' => 10, 'max' => 1000],
        ];
    }
    
    /*
     * Связь с таблицей Категории услуг/Вид услуг
     */
    public function getCategory() {
        return $this->hasOne(CategoryServices::className(), ['category_id' => 'service_category_id']);
    }
    
    /*
     * Связь с таблицей Единицы измерения
     */
    public function getUnit() {
        return $this->hasOne(Units::className(), ['units_id' => 'service_unit_id']);
    }
    
    /*
     * Формирование массива услуг
     */
    public static function getServicesNameArray() {
        
        $array = static::find()
                ->asArray()
                ->all();
        
        return ArrayHelper::map($array, 'service_id', 'service_name');
    }

    /*
     * Формируем список услуг по заданной категории
     */
    public static function getPayServices($category_id) {
        
        $pay_services = self::find()
                ->joinWith('category')
                ->andWhere(['service_category_id' => $category_id])
                ->asArray()
                ->orderBy(['category_name' => SORT_ASC, 'service_name' => SORT_ASC])
                ->all();
        
        return $pay_services;
    }
    
    /*
     * Получить ID услуги
     */
    public function getId() {
        return $this->service_id;
    }
    
    /*
     * Поиск услуги по ID
     */
    public static function findByID($service_id) {
        return self::find()
                ->where(['service_id' => $service_id])
                ->one();
    }
    
    /*
     * Загрузка изображения услуги
     */    
    public function uploadImage($file) {
        
        $current_image = $this->services_image;
        
        if ($this->validate()) {
            if ($file) {
                $this->service_image = $file;
                $dir = Yii::getAlias('images/services/');
                $file_name = 'service_' . time() . '.' . $this->service_image->extension;
                $this->service_image->saveAs($dir . $file_name);
                $this->service_image = '/' . $dir . $file_name;
                @unlink(Yii::getAlias('@webroot' . $current_image));
            } else {
                $this->service_image = $current_image;
            }
            return $this->save() ? true : false;
        }
        
        return false;
    }
    
    /*
     * Получить изображение услуги
     * В случае, если изображени для услуги не было задано - выводится изображение по умолчанию
     */
    public function getImage() {
        if (empty($this->service_image)) {
            return Yii::getAlias('@web') . '/images/not_found.png';
        }
        return Yii::getAlias('@web') . $this->service_image;
    }

    
    /**
     * Массив статусов заявок
     */
    public function attributeLabels()
    {
        return [
            'service_id' => 'ID',
            'service_category_id' => 'Категория',
            'service_name' => 'Наименование',
            'service_unit_id' => 'Единица измерения',
            'service_price' => 'Цена',
            'service_description' => 'Описание',
            'service_image' => 'Изображение',
        ];
    }
}
