<?php

    namespace app\modules\managers\models\form;
    use yii\base\Model;

/**
 *  Форма показаний приборов учета
 */
class CounterIndicationsForm extends Model {
    
    public $counter_id_client;
    public $previous_indication;
    public $current_indication;

    public function rules() {
        
        return [
            [['counter_id_client', 'current_indication'], 'required'],
        ];
        
    }
    
    public function attributeLabels() {
        
        return [
            'counter_id_client' => 'Уникальный идентификатор',
            'current_indication' => 'Текущие показания',
        ];
    }
    
}
