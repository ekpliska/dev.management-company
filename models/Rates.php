<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Units;
    use app\models\Services;

/**
 * Тарифы
 */
class Rates extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'rates';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['rates_service_id', 'rates_unit_id'], 'integer'],
//            ['rates_cost', 'number'],
        ];
    }
    
    /*
     * Связь с таблицей Единицы измерения
     */
    public function getUnit() {
        return $this->hasOne(Rates::className(), ['units_id' => 'rates_unit_id']);
    }

    /*
     * Связь с таблицей Услуги
     */
    public function getService() {
        return $this->hasOne(Services::className(), ['services_id' => 'rates_service_id']);
    }
    
    /*
     * Поиск тарифа по ID услуги
     */
    public static function findByServiceID($service_id) {
        return self::find()
                ->where(['rates_service_id' => $service_id])
                ->one();
    }
    
    
    /**
     * Массив статусов заявок
     */
    public function attributeLabels()
    {
        return [
            'rates_id' => 'Rates ID',
            'rates_service_id' => 'Rates Service ID',
            'rates_unit_id' => 'Rates Units',
            'rates_cost' => 'Rates Cost',
        ];
    }
}
