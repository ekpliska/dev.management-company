<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\TypeCounters;
    use app\models\ReadingCounters;

/**
 * Приборы учета
 */
class Counters extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName() {
        return 'counters';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['counters_type_id', 'counters_account_id', 'counters_house_id', 'date_check', 'isActive'], 'integer'],
            [['counters_description'], 'string', 'max' => 1000],
            [['counters_number'], 'string', 'max' => 70],
        ];
    }
    
    /*
     * Связь с таблицей "Типы приборов учета"
     */
    public function getTypeCounter() {
        return $this->hasOne(TypeCounters::className(), ['type_counters_id' => 'counters_type_id']);
    }
    
    /*
     * Связь с таблицей "Показания приборов учета"
     */
    public function getReadingCounter() {
        return $this->hasMany(ReadingCounters::className(), ['reading_counter_id' => 'counters_id']);
    }

    /**
     * Метки для полей
     */
    public function attributeLabels() {
        return [
            'counters_id' => 'Counters ID',
            'counters_type_id' => 'Counters Type ID',
            'counters_number' => 'Counters Number',
            'counters_description' => 'Counters Description',
            'counters_account_id' => 'Counters Account ID',
            'counters_house_id' => 'Counters House ID',
            'date_check' => 'Date Check',
            'isActive' => 'Is Active',
        ];
    }
}
