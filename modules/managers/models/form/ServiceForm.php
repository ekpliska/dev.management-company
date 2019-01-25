<?php

    namespace app\modules\managers\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\Services;

/**
 * Новая услуга
 */
class ServiceForm extends Model {

    public $service_name;
    public $service_category;
    public $service_price;
    public $service_unit;
    public $service_description;
    public $service_image;
    
    public function rules() {
        return [
            [[
                'service_name', 'service_category',
                'service_price', 'service_unit'], 'required'],
            
            [['service_name', 'service_description'], 'string', 'min' => 3, 'max' => 255],
            
            [['service_name'],
                'match', 
                'pattern' => '/^[А-Яа-я\0-9\ \_\-]+$/iu', 
                'message' => 'Вы используете запрещенные символы',
            ],
            
            ['service_price', 'double', 'min' => 00.00, 'max' => 100000.00, 'message' => 'Цена услуги указана не верно. Пример: 790.70'],
            ['service_price', 'validatePrice'],
            
            ['service_name', 'unique', 
                'targetClass' => Services::className(),
                'targetAttribute' => 'service_name',
                'message' => 'Указанная услуга существует',
            ],
            
            [['service_image'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['service_image'], 'image', 'maxWidth' => 510, 'maxHeight' => 510],
            
        ];
    }
    
    /*
     * Валидация поля Тариф
     * Тариф не может быть равен нулю и быть отрицательным
     */
    public function validatePrice() {
        if ($this->service_price <= 0) {
            $this->addError('service_price', 'Значение тарифа не может быть отрицательным или равным нулю');
        }
    }
    
    public function save($file) {
        
        if (!$this->validate()) {
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            $service = new Services();
            $service->service_category_id = $this->service_category;            
            $service->service_name = $this->service_name;
            $service->service_unit_id = $this->service_unit;
            $service->service_price = $this->service_price;
            $service->service_description = $this->service_description;
            $service->uploadImage($file);
            
            if (!$service->save()) {
                Yii::$app->session->setFlash('error', ['message' => $service->getFirstErrors()]);
                return false;
            }
            
            $transaction->commit();
            return $service->service_id;
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        
        
    }
    
    public function attributeLabels() {
        return [
            'service_name' => 'Наименование услуги',
            'service_category' => 'Вид услуги',
            'service_price' => 'Стоимость',
            'service_unit' => 'Единица измерения',
            'service_type' => 'Тип услуги',
            'service_description' => 'Описание',
            'service_image' => 'Изображение',
        ];
    }
    
}
