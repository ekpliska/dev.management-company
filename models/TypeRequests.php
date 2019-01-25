<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;

/**
 * Вид заявок
 */
class TypeRequests extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'type_requests';
    }
    
    /**
     * Правила валидации
     */
    public function rules() {
        return [
            
            ['type_requests_name', 'required'],
            
            [['type_requests_name'], 'string', 'min' => 3, 'max' => 255],
        ];
    }
    
    /*
     * Формирование видов (типов) заявок
     */
    public static function getTypeNameArray() {
        $array = static::find()->asArray()->all();
        return ArrayHelper::map($array, 'type_requests_id', 'type_requests_name');
    }

    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'type_requests_id' => 'ID',
            'type_requests_name' => 'Наименование',
            'type_requests_description' => 'Описание',
        ];
    }
}
