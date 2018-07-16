<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

/**
 * Единицы измерения
 */
class Units extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'units';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['units_name'], 'string', 'max' => 10],
        ];
    }

    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'units_id' => 'Units ID',
            'units_name' => 'Units Name',
        ];
    }
}
