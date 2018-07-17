<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Rates;
    use app\models\CategoryServices;
    use yii\helpers\ArrayHelper;

/**
 * Услуги
 */
class Services extends ActiveRecord
{
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
            [['services_category_id', 'isPay', 'isServices'], 'integer'],
            [['services_name', 'services_image'], 'string', 'max' => 255],
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
    
    /**
     * Массив статусов заявок
     */
    public function attributeLabels()
    {
        return [
            'services_id' => 'Services ID',
            'services_name' => 'Services Name',
            'services_category_id' => 'Services Category ID',
            'isPay' => 'Is Pay',
            'isServices' => 'Is Services',
            'services_image' => 'Services Image',
        ];
    }
}
