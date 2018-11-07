<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

class SmsOperations extends ActiveRecord
{
    
    const TYPE_CHANGE_PASSWORD = 1;

    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'sms_operations';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['operations_type', 'user_id', 'sms_code', 'date_registration'], 'required'],
            [['operations_type', 'user_id', 'sms_code', 'date_registration', 'status'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }
    
    public static function findByUserIDAndType($user_id, $type_id) {
        
        $array = self::find()
                ->where(['user_id' => $user_id, 'operations_type' => $type_id])
                ->asArray()
                ->one();
        
        return $array == null ? false : true; 
        
    }

    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operations_type' => 'Operations Type',
            'user_id' => 'User ID',
            'sms_code' => 'Sms Code',
            'value' => 'Value',
            'date_registration' => 'Date Registration',
            'status' => 'Status',
        ];
    }
}
