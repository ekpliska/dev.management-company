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
    public function getHouse()
    {
        return $this->hasMany(Houses::className(), ['houses_estate_name_id' => 'estate_id']);
    }
    
    public static function getHousingEstateList() {
        $array = (new \yii\db\Query)
                ->select('estate_id as estate_id, estate_name as name, houses_town as town')
                ->from('housing_estates as he')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_estate_name_id = he.estate_id')
                ->orderBy(['name' => SORT_ASC])
                ->groupBy('name')
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
        ];
    }

}
