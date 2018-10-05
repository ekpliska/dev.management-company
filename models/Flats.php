<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Houses;

/**
 * Квартиры
 */
class Flats extends ActiveRecord
{
    /**
     * Таблица в БД
     */
    public static function tableName()
    {
        return 'flats';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['flats_house_id', 'flats_porch', 'flats_floor', 'flats_number', 'flats_rooms', 'flats_square'], 'required'],
            [['flats_house_id', 'flats_porch', 'flats_floor', 'flats_number', 'flats_rooms', 'flats_square', 'flats_account_id', 'flats_client_id'], 'integer'],
            [['flats_house_id'], 'exist', 'skipOnError' => true, 'targetClass' => Houses::className(), 'targetAttribute' => ['flats_house_id' => 'houses_id']],
        ];
    }

    /**
     * Связь с таблицей Дома
     */
    public function getHouse()
    {
        return $this->hasOne(Houses::className(), ['houses_id' => 'flats_house_id']);
    }    
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'flats_id' => 'Flats ID',
            'flats_house_id' => 'Flats House ID',
            'flats_porch' => 'Flats Porch',
            'flats_floor' => 'Flats Floor',
            'flats_number' => 'Flats Number',
            'flats_rooms' => 'Flats Rooms',
            'flats_square' => 'Flats Square',
            'flats_account_id' => 'Flats Account ID',
            'flats_client_id' => 'Flats Client ID',
        ];
    }

}
