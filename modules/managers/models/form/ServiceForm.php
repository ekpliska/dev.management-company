<?php

    namespace app\modules\managers\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\Services;
    use app\models\Rates;

/**
 * Новая услуга
 */
class ServiceForm extends Model {

    public $service_name;
    public $service_category;
    public $service_rate;
    public $service_unit;
    public $service_type;
    public $service_description;
    public $service_image;
    
    public function rules() {
        return [
            [[
                'service_name', 'service_category', 'service_type',
                'service_rate', 'service_unit'], 'required'],
        
            [['service_name', 'service_description'], 'string', 'min' => 3, 'max' => 255],
            
        ];
    }
    
    public function save() {
        if (!$this->validate()) {
            return false;
        }
        
        try {
            
        } catch (Exception $ex) {
            
        }
        
        $transaction = Yii::$app->db->beginTransaction();
    }
    
    public function attributeLabels() {
        return [
            'service_name' => 'Наименование услуги',
            'service_category' => 'Вмд услуги',
            'service_rate' => 'Тариф',
            'service_unit' => 'Единицв измерения',
            'service_type' => 'Тип услуги',
            'service_description' => 'Описание услуги',
        ];
    }
    
}
