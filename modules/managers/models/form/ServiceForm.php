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
            ['service_name', 
                'match', 
                'pattern' => '/^[А-Яа-яЁё0-9\ \-\(\)]+$/iu'],
            
            ['service_rate', 'number', 'numberPattern' => '/^\d+(.\d{1,2})?$/'], 
            ['service_rate', 'validateRate'],
            
            [['service_image'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['service_image'], 'image', 'maxWidth' => 510, 'maxHeight' => 510],
            
        ];
    }
    
    /*
     * Валидация поля Тариф
     * Тариф не может быть равен нулю и быть отрицательным
     */
    public function validateRate() {
        if ($this->service_rate <= 0) {
            $this->addError('service_rate', 'Значение тарифа не может быть отрицательным или равным нулю');
        }
    }
    
    public function save($file) {
        
        if (!$this->validate()) {
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            $service = new Services();
            $service->services_name = $this->service_name;
            $service->services_category_id = $this->service_category;
            $service->services_description = $this->service_description;
            $service->isType = $this->service_type;
            $service->uploadImage($file);
            
            if (!$service->save()) {
                Yii::$app->session->setFlash('services-admin', [
                    'success' => false,
                    // 'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                    'error' => $service->getFirstErrors(),
                ]);
                /* var_dump(($service->errors));
                die(); */
                return false;
            }
            
            $rate = new Rates();
            $rate->rates_unit_id = $this->service_unit;
            $rate->rates_cost = $this->service_rate;
            $rate->link('service', $service);
            
            if (!$rate->save()) {
                Yii::$app->session->setFlash('services-admin', [
                    'success' => false,
                    // 'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                    'error' => $rate->getFirstErrors(),
                ]);
                /* var_dump(($rate->errors));
                die(); */
                return false;
            }
            
            $transaction->commit();
            return $service->services_id;
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        
        
    }
    
    public function attributeLabels() {
        return [
            'service_name' => 'Наименование услуги',
            'service_category' => 'Вид услуги',
            'service_rate' => 'Стоимость',
            'service_unit' => 'Единицв измерения',
            'service_type' => 'Тип услуги',
            'service_description' => 'Описание услуги',
        ];
    }
    
}
