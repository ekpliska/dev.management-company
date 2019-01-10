<?php

    namespace app\modules\clients\models\form;
    use yii\base\Model;

/**
 * Форма отправки текущих показаний приборов учета
 */
class SendIndicationForm extends Model {
    
    public $counter_number;
    public $current_indication;
    public $previous_indication;
    
    public function rules() {
        
        return [
            ['current_indication', 'required', 'message' => 'Укажите показания'],
            ['counter_number', 'integer'],
            [['current_indication', 'previous_indication'], 'double', 'message' => 'Некорректные показания'],
            
            ['current_indication', 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number', 'message' => 'Некорректные показания'],
            
        ];        
    }
    
}
