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
     * 
     * --------------------------------> ВРЕМЕННЫЙ КОСТЫЛЬ
     * 
     */
    public static function getReadingCurrent($account_id, $month) {
        
        $last_indication = (new \yii\db\Query())
                ->select('reading_counter_id, date_reading, readings_indication')
                ->from('reading_counters')
                ->where(['=', 'MONTH(FROM_UNIXTIME(date_reading))',  $month - 1])
                ;
 
        $current_indication = (new \yii\db\Query())
                ->select("reading_counter_id, date_reading, readings_indication")
                ->from('reading_counters')
                ->where(['=', 'MONTH(FROM_UNIXTIME(date_reading))',  $month])
                ;


        $query_indication = (new \yii\db\Query())
                ->select('t1.counters_number, t1.counters_id, t1.date_check, t4.type_counters_name,'
                        . 't2.date_reading as last_date, t2.readings_indication as last_ind,'
                        . 't3.date_reading as current_date, t3.readings_indication as current_ind')
                ->from('counters AS t1')
                ->join('LEFT JOIN', 'reading_counters as t2', 't1.counters_id = t2.reading_counter_id')
                ->join('LEFT JOIN', 'reading_counters as t3', 't1.counters_id = t3.reading_counter_id')
                ->join('LEFT JOIN', 'type_counters AS t4', 't1.counters_id = t4.type_counters_id')
                ->where(['t1.counters_account_id' => $account_id])
                ->where(['=', 'MONTH(FROM_UNIXTIME(t2.date_reading))',  $month - 1])
                ->where(['=', 'MONTH(FROM_UNIXTIME(t3.date_reading))',  $month])
                ->groupBy('counters_id');

         /*
         *  Проверяем наличие показаний за текущий месяц
         */
//        if ($current_indication->all()) {
//            // Показания за текущий месяц существуют
//            $select_param = 
//                    'info.counters_number, info.counters_type_id, info.date_check, info.counters_account_id,'
//                    . 'name.type_counters_name, '
//                    . 'r1.reading_counter_id, '
//                    . 'r1.date_reading as date_last, r1.readings_indication as ind_last, '
//                    . 'r2.date_reading as date_current, r2.readings_indication as ind_current';
//            
//            $from_param = [
//                'name' => 'type_counters', 
//                'info' => 'counters', 
//                'r1' => $last_indication, 
//                'r2' => $current_indication
//            ];
//            
//        } else {
//            // Показания за текущий месяц не существуют
//            $select_param = (
//                    'info.counters_number, info.counters_type_id, info.date_check, '
//                    . 'name.type_counters_name, '
//                    . 'r1.reading_counter_id, '
//                    . 'r1.date_reading as date_last, r1.readings_indication as ind_last'
//                    );
//            
//            $from_param = [
//                'name' => 'type_counters', 
//                'info' => 'counters', 
//                'r1' => $last_indication
//            ];
//        }
//            
//        $unionQuery = (new \yii\db\Query())
//                ->select($select_param)
//                ->from($from_param)
//                ->where(['info.counters_account_id' => $account_id])
//                ->where('name.type_counters_id = info.counters_id')
//                ->groupBy('r1.reading_counter_id')
//                ;

        //return $unionQuery;
        
        return $query_indication;

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
