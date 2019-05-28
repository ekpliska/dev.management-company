<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;


/**
 * Организации, предоставляющие услуги ЖКХ
 */
class Organizations extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'organizations';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [[
                'organizations_name', 'email', 'phone', 'dispatcher_phone',
                'town', 'street', 'house'], 'string', 'min' => 5, 'max' => 100],
            
            ['time_to_work', 'string', 'min' => 10, 'max' => 255],
            
            [[
                'postcode', 'inn', 'kpp', 'checking_account', 
                'ks', 'bic'], 'integer'],
            
        ];
    }
    
    public function getName() {
        return $this->organizations_name;
    }
    
    public function getFullAddress() {
        
        return $this->postcode
                . ', г. ' . $this->town
                . ', ул. ' . $this->street
                . ', д. ' . $this->house;
    }
    
    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'organizations_name' => 'Наименование ограницации',
            'email' => 'Электронный адрес',
            'phone' => 'Телефон',
            'dispatcher_phone' => 'Телефон диспетчерской службы',
            'postcode' => 'Почтовый индекс',
            'town' => 'Город',
            'street' => 'Улица/Проспект/Проеезд/Площадь',
            'house' => 'Дом/Строение/Корпус',
            'time_to_work' => 'Часты работы',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'checking_account' => 'Расчетный счет',
            'ks' => 'КС',
            'bic' => 'БИК',
        ];
    }
}
