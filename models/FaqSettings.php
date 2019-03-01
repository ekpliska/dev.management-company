<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

/**
 * FAQ
 */
class FaqSettings extends ActiveRecord {
    
    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'faq_settings';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['faq_question', 'faq_answer'], 'required'],
            [['faq_answer'], 'string'],
            [['faq_question'], 'string', 'min' => 10, 'max' => 100],
            [['faq_answer'], 'string', 'min' => 10, 'max' => 500],
        ];
    }

    /**
     * Аттрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'faq_question' => 'Вопрос',
            'faq_answer' => 'Ответ',
        ];
    }
}
