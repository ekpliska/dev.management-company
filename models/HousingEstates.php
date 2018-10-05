<?php

    namespace app\models;
    use Yii;
    use app\models\Houses;
    use yii\db\ActiveRecord;

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
            [['estate_name', 'houses_town'], 'required'],
            [['estate_name'], 'string', 'max' => 170],
            [['estat_town'], 'string', 'max' => 70],
            [['estat_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * Связь с таблицей Дома
     */
    public function getHouse()
    {
        return $this->hasMany(Houses::className(), ['houses_estate_name_id' => 'estate_id']);
    }
    
    public static function getHousingEstateList() {
        
        $array = self::find()
                ->asArray()
                ->orderBy(['estate_name' => SORT_ASC])
                ->all();        
        
        return $array;
    }    
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'estate_id' => 'Estate ID',
            'estate_name' => 'Estate Name',
            'estat_town' => 'Houses Town',
            'estat_description' => 'Houses Description',
        ];
    }

}
