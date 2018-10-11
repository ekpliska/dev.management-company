<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Houses;

/**
 *  Характеристики дома
 */
class CharacteristicsHouse extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'characteristics_house';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['characteristics_house_id', 'characteristics_name', 'characteristics_value'], 'required'],
            [['characteristics_house_id'], 'integer'],
            [['characteristics_name'], 'string', 'max' => 255],
            [['characteristics_value'], 'string', 'max' => 170],
            [['characteristics_house_id'], 'exist', 'skipOnError' => true, 'targetClass' => Houses::className(), 'targetAttribute' => ['characteristics_house_id' => 'houses_id']],
        ];
    }

    /**
     * Связь с таблицей Дома
     */
    public function getHouse() {
        return $this->hasOne(Houses::className(), ['houses_id' => 'characteristics_house_id']);
    }    
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'characteristics_id' => 'Characteristics ID',
            'characteristics_house_id' => 'Characteristics House ID',
            'characteristics_name' => 'Characteristics Name',
            'characteristics_value' => 'Characteristics Value',
        ];
    }

}
