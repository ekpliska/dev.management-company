<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\validators\UrlValidator;

/**
 * Настройки срайдера
 */
class SliderSettings extends ActiveRecord {
    
    const STATUS_SHOW = 1;
    const STATUS_HIDE = 0;

    /**
     * Таблица в БД
     */
    public static function tableName() {
        return 'slider_settings';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        
        return [
            ['slider_title', 'required'],
            [['slider_title'], 'string', 'max' => 100],
            [['slider_text', 'button_1', 'button_1'], 'string', 'max' => 255],
            [['button_1', 'button_2'], UrlValidator::className()],
            ['is_show', 'integer'],
        ];
        
    }

    /**
     * Атрибуты полей
     */
    public function attributeLabels() {
        return [
            'slider_id' => 'Slider ID',
            'slider_title' => 'Заголовок',
            'slider_text' => 'Краткий комментарий',
            'button_1' => 'Ссылка на AppStore приложение',
            'button_2' => 'Ссылка на GooglePlay приложение',
            'is_show' => 'Показывать слайдер на главной'
        ];
    }
}
