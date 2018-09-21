<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Rates;
    use app\models\CategoryServices;

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
                'services_name',
                'services_category_id', 'isType'], 'required'],
            
            [['services_category_id', 'isPay', 'isServices', 'isType'], 'integer'],
            
            [['services_name', 'services_image'], 'string', 'min' => '10', 'max' => 255],
            
            ['services_description', 'string', 'min' => 10, 'max' => 1000],
        ];
    }
    
    /*
     * Связь с таблицей Тарифы
     */
    public function getRate() {
        return $this->hasOne(Rates::className(), ['rates_service_id' => 'services_id']);
    }        

    /*
     * Связь с таблицей Категории услуг
     */
    public function getCategory() {
        return $this->hasOne(CategoryServices::className(), ['category_id' => 'services_category_id']);
    }
    
    /*
     * Формирование массива услуг
     */
    public static function getServicesNameArray() {
        $array = static::find()->asArray()->all();
        return ArrayHelper::map($array, 'services_id', 'services_name');
    }

    /*
     * Получаем только платные услуги
     */
    public static function getPayServices() {
        $pay_services = self::find()
                ->andWhere(['isPay' => '1'])
                ->asArray()
                ->all();
        
        return ArrayHelper::map($pay_services, 'services_id', 'services_name');
    }
    
    /*
     * Список типов услуг
     */
    public static function getTypeNameArray () {
        return [
            self::TYPE_SERVICE => 'Услуга',
            self::TYPE_PAY => 'Платная услуга',
        ];
    }
    
    /*
     * 
     */
    /*
     * Загрузка изображения услуги
     */    
    public function uploadImage($file) {
        
        $current_image = $this->services_image;
        
        if ($this->validate()) {
            if ($file) {
                $this->services_image = $file;
                $dir = Yii::getAlias('images/services/');
                $file_name = 'service_' . time() . '.' . $this->services_image->extension;
                $this->services_image->saveAs($dir . $file_name);
                $this->services_image = '/' . $dir . $file_name;
                @unlink(Yii::getAlias('@webroot' . $current_image));
            } else {
                $this->services_image = $current_image;
            }
            return $this->save() ? true : false;
        }
        
        return false;
    }

    
    /**
     * Массив статусов заявок
     */
    public function attributeLabels()
    {
        return [
            'services_id' => 'Services ID',
            'services_name' => 'Наименование услуги',
            'services_category_id' => 'Вид услуги',
            'isPay' => 'Is Pay',
            'isServices' => 'Is Services',
            'services_image' => 'Изображение',
            'services_description' => 'Описание',
            'isType' => 'Тип услуги',
        ];
    }
}
