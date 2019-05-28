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
            [['welcome_text', 'promo_block'], 'string', 'max' => 1000],
            [['api_url', 'url_get_receipts'], 'string', 'max' => 100],
            [['user_agreement'], 'string', 'min' => 10, 'max' => 2000],
            [['api_url', 'url_get_receipts'], UrlValidator::className(), 'message' => 'Не верный формат адреса']
        ];
    }

    /**
     * Аттрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'api_url' => 'URL адрес API',
            'url_get_receipts' => 'Адрес хранения квитанций',
            'welcome_text' => 'Текст приветствия на главной странице',
            'user_agreement' => 'Пользовательское соглашение',
            'promo_block' => 'Промоблок',
        ];
    }
}
