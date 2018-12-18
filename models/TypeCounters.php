<?php

    namespace app\models;
    use Yii;
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
            [['type_counters_name'], 'string', 'max' => 100],
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
     * Возвращает ID типа прибора учета
     */
    public static function getTypeID($name) {
        
        $array = self::find()
                ->where(['type_counters_name' => $name])
                ->asArray()
                ->one();
        return $array['type_counters_id'];
        
    }

    /**
     * Метки для полей
     */
    public function attributeLabels() {
        return [
            'type_counters_id' => 'Type Counters ID',
            'type_counters_name' => 'Type Counters Name',
        ];
    }
}
