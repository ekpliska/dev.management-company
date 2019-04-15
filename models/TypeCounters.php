<?php

    namespace app\models;
    use yii\db\ActiveRecord;
    use app\models\Counters;
    use yii\helpers\ArrayHelper;

/**
 * Виды приборов учета
 */
class TypeCounters extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName() {
        return 'type_counters';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['type_counters_name', 'type_counters_image'], 'string', 'max' => 100],
        ];
    }
    
    public function getCounter() {
        return $this->hasMany(Counters::className(), ['counters_type_id' => 'type_counters_id']);
    }
    
    /*
     * Список типов приборов учета
     */
    public static function getTypeCountersLists() {
        
        $array = self::find()->asArray()->all();
        
        return ArrayHelper::map($array, 'type_counters_id', 'type_counters_name');
        
    }
    
    /*
     * Получить изображение прибора учета
     */
    public static function getImageCounter($name) {
        
        $array = self::find()
                ->where(['type_counters_name' => $name])
                ->asArray()
                ->one();
        return $array['type_counters_image'];
        
    }

    /**
     * Метки для полей
     */
    public function attributeLabels() {
        return [
            'type_counters_id' => 'Type Counters ID',
            'type_counters_name' => 'Type Counters Name',
            'type_counters_image' => 'Type Counters Image',
        ];
    }
}
