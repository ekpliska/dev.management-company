<?php

    namespace app\models;
    use Yii;

/**
 * Организации, предоставляющие услуги ЖКХ
 */
class Organizations extends \yii\db\ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'organizations';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['organizations_name'], 'string', 'max' => 70],
        ];
    }

    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'organizations_id' => 'Organizations ID',
            'organizations_name' => 'Organizations Name',
        ];
    }
}
