<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

/**
 * Вид заявок
 */
class TypeRequests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'type_requests';
    }
    
    public static function getTypeNameArray() {
        $array = static::find()->asArray()->all();
        return \yii\helpers\ArrayHelper::map($array, 'type_requests_id', 'type_requests_name');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_requests_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'type_requests_id' => 'Type Requests ID',
            'type_requests_name' => 'Type Requests Name',
        ];
    }
}
