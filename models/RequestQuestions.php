<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\TypeRequests;

/**
 * Вопросы для опроса завершенных заявок
 * (Конструктор заявок)
 */
class RequestQuestions extends ActiveRecord {
    
    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'request_questions';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['type_request_id', 'question_text'], 'required', 'message' => 'Поле обязательно для заполнения'],
            [['type_request_id'], 'integer'],
            [['question_text'], 'string', 'min' => 10, 'max' => 255],
            [['type_request_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeRequests::className(), 'targetAttribute' => ['type_request_id' => 'type_requests_id']],
        ];
    }

    /**
     * Связь с таблицей Виды Заявки
     */
    public function getTypeRequest() {
        return $this->hasOne(TypeRequests::className(), ['type_requests_id' => 'type_request_id']);
    }
    
    /**
     * Связь с таблицей Ответы
     */
    public function getAnswers() {
        return $this->hasOne(RequestAnswers::className(), ['anwswer_question_id' => 'question_id']);
    }    
    
    /*
     * Получить список вопросов для указанного Вида заявки
     */
    public static function getAllQuestions($type_request) {
        
        return self::find()
                ->where(['type_request_id' => $type_request])
                ->asArray()
                ->all();
        
    }
    
    /**
     * Атрибуты полей
     */
    public function attributeLabels() {
        return [
            'question_id' => 'ID',
            'type_request_id' => 'Вид заявки',
            'question_text' => 'Текст вопроса',
        ];
    }

}
