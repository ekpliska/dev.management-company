<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\validators\UrlValidator;

/**
 * Настройки API Заказчика
 */
class ApiSettings extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'api_settings';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['api_url'], 'required', 'message' => 'Укажите url-адрес'],
            [['api_url'], 'string', 'max' => 100],
            ['api_url', UrlValidator::className(), 'message' => 'Не верный формат адреса']
        ];
    }

    /**
     * Аттрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'api_url' => 'URL адрес ',
        ];
    }
}
