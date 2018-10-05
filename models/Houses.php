<?php

    namespace app\models;
    use yii\db\ActiveRecord;
    use Yii;
    use app\models\Flats;
    use app\models\HousingEstates;

/**
 * Дома
 */
class Houses extends ActiveRecord
{
    /**
     * Таблица в БД
     */
    public static function tableName()
    {
        return 'houses';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['houses_estate_name_id', 'houses_street', 'houses_number_house'], 'required'],
            [['houses_estate_name_id'], 'integer'],
            [['houses_street'], 'string', 'max' => 100],
            [['houses_number_house'], 'string', 'max' => 10],
            [['houses_estate_name_id'], 'exist', 'skipOnError' => true, 'targetClass' => HousingEstates::className(), 'targetAttribute' => ['houses_estate_name_id' => 'estate_id']],
        ];
    }

    /**
     * Связь с таблицей Квартиры
     */
    public function getFlat()
    {
        return $this->hasMany(Flats::className(), ['flats_house_id' => 'houses_id']);
    }

    /**
     * Связь с таблицей Жилой комплекс
     */
    public function getEstate()
    {
        return $this->hasOne(HousingEstates::className(), ['estate_id' => 'houses_estate_name_id']);
    }    
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'houses_id' => 'Houses ID',
            'houses_estate_name_id' => 'Houses Estate Name ID',
            'houses_street' => 'Houses Street',
            'houses_number_house' => 'Houses Number House',
        ];
    }


}
