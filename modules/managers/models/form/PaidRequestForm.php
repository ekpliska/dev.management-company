<?php

    namespace app\modules\managers\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\PersonalAccount;
    use app\models\Clients;
    use app\models\Rents;
    use app\models\StatusRequest;

/**
 * Заявка на платную услугу
 */
class PaidRequestForm extends Model {
   
    public $servise_category;
    public $servise_name;
    public $phone;
    public $flat;
    public $description;
    
    /*
     *  Правила валидации
     */
    public function rules() {
        return [
            [['servise_category', 'servise_name', 'phone', 'description', 'flat'], 'required'],
            
            ['description', 'string', 'min' => 10, 'max' => 255],
            ['description', 'match',
                'pattern' => '/^[А-Яа-яЁёA-Za-z0-9\_\-\@\.]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы русского и английского алфавита, цифры, знаки "-", "_"',
            ],            
            
            ['phone', 'existenceClient'],
            ['phone',
                'match', 
                'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i',
            ],
        ];
    }
    
    /*
     * Проверяет наличие собственника или арендатора по указанному номеру телефона
     */
    public function existenceClient() {
        
        $client = Clients::find()
                ->andWhere(['clients_mobile' => $this->phone])
                ->orWhere(['clients_phone' => $this->phone])
                ->one();

        $rent = Rents::find()
                ->andwhere(['rents_mobile' => $this->phone])
                ->orWhere(['rents_mobile_more' => $this->phone])
                ->one();
        
        if ($client == null && $rent == null) {
            $errorMsg = 'Собственник или арендатор по указанному номеру мобильного телефона на найден. Укажите существующий номер телефона';
            $this->addError('phone', $errorMsg);
        }

    }
    
    public function save() {
        
    }
    
    /*
     * Атрибуты полей
     */
    public function attributeLabels() {
        return [
            'servise_category' => 'Вид услуги',
            'servise_name' => 'Наименование услуги',
            'phone' => 'Мобильный телефон',
            'flat' => 'Адрес',
            'description' => 'Описание заявки',
        ];
    }
    
}
