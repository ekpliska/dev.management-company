<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use app\models\RequestQuestions;
    use app\models\Requests;

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
            
            ['type_requests_name', 'required', 'message' => 'Поле обязательно для заполнения'],
            
            [['type_requests_name'], 'string', 'min' => 3, 'max' => 255],
        ];
    }
    
    /*
     * Связь с талиблицей Виды заявок
     */
    public function getRequest() {
        return $this->hasMany(Requests::className(), ['requests_type_id' => 'type_requests_id']);
    }       

    
    /**
     * Связь с таблцией Вопросы для заявки (Опрос)
     */
    public function getQuestions() {
        return $this->hasMany(RequestQuestions::className(), ['type_request_id' => 'type_requests_id']);
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
        ];
    }
}
