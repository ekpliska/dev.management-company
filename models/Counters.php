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
    
    public static function findCountersClient($account_id) {
        
        return self::find()
                ->andWhere(['counters_account_id' => $account_id])
                ->with(['typeCounter', 'readingCounter']);
        
    }
    
    /*
     * Получить показания приборов учета по номеру текущего месяца
     * @param $last_indication Показания предыдущего месяца от текущего
     * @param $current_indication Показания текущего месяца
     */
    public static function getReadingCurrent($account_id, $month) {
        
        $last_indication = (new \yii\db\Query())
            ->select("reading_counter_id, date_reading, readings_indication")
            ->from('reading_counters')
            ->where(['=', 'MONTH(FROM_UNIXTIME(date_reading))',  $month - 1]);
        
        $current_indication = (new \yii\db\Query())
            ->select("reading_counter_id, date_reading, readings_indication")
            ->from('reading_counters')
            ->where(['=', 'MONTH(FROM_UNIXTIME(date_reading))',  $month]);
        
        /*
         *  Проверяем наличие показаний за текущий месяц
         */
        if ($current_indication->all()) {
            // Показания за текущий месяц существуют
            $select_param = 
                    'info.counters_number, info.counters_type_id, info.date_check, '
                    . 'name.type_counters_name, '
                    . 'r1.reading_counter_id, '
                    . 'r1.date_reading as date_last, r1.readings_indication as ind_last, '
                    . 'r2.date_reading as date_current, r2.readings_indication as ind_current';
            
            $from_param = [
                'name' => 'type_counters', 
                'info' => 'counters', 
                'r1' => $last_indication, 
                'r2' => $current_indication
            ];
            
        } else {
            // Показания за текущий месяц не существуют
            $select_param = (
                    'info.counters_number, info.counters_type_id, info.date_check, '
                    . 'name.type_counters_name, '
                    . 'r1.reading_counter_id, '
                    . 'r1.date_reading as date_last, r1.readings_indication as ind_last'
                    );
            
            $from_param = [
                'name' => 'type_counters', 
                'info' => 'counters', 
                'r1' => $last_indication
            ];
        }
            
        $unionQuery = (new \yii\db\Query())
            ->select($select_param)
            ->from($from_param)
            ->where(['info.counters_account_id' => $account_id])
            ->groupBy('info.counters_type_id');
        
        return $unionQuery;

    }

    /*
     * Получить показания приборов учетов за текущий месяц
     */
    public static function getReadingCurrentMonth($account_id) {

        $current_date = date('n');
        
        return self::find()
                ->joinWith('typeCounter as t')
                ->joinWith('readingCounter as r')
                ->andWhere(['counters_account_id' => $account_id])
                ->andWhere(['=', 'MONTH(FROM_UNIXTIME(r.date_reading))', $current_date]);
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
