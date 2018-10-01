<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Houses;

/**
 * Жилой комплекс
 */
class HousingEstates extends ActiveRecord
{
    /**
     * Таблица в БД
     */
    public static function tableName()
    {
        return 'housing_estates';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['estate_name'], 'required'],
            [['estate_name'], 'string', 'max' => 170],
        ];
    }
    
    /**
     * Связь с таблицей дома
     */
    public function getHouses()
    {
        return $this->hasMany(Houses::className(), ['houses_estate_name_id' => 'estate_id']);
    }    

    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'estate_id' => 'Estate ID',
            'estate_name' => 'Estate Name',
        ];
    }

}
