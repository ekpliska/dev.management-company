<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\validators\UrlValidator;

/**
 * Настройки API Заказчика
 */
class SiteSettings extends ActiveRecord {
    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'site_settings';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['api_url', 'user_agreement'], 'required', 'message' => 'Поле обязательно для заполнения'],
            [['welcome_text'], 'string', 'max' => 1000],
            [['api_url'], 'string', 'max' => 100],
            [['user_agreement'], 'string', 'min' => 10, 'max' => 2000],
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
            'welcome_text' => 'Текст приветствия на главной странице',
            'user_agreement' => 'Пользовательское соглашение',
        ];
    }
}
