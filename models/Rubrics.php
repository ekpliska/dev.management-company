<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use app\models\News;

/**
 * Рубрика / Тип публикации
 */
class Rubrics extends ActiveRecord {
    
    // Важная инофрмация
    const RUBRIC_INFORMATION = 1;
    // Специальные предложения
    const RUBRIC_ADVERTS = 2;
    // Новости дома
    const RUBRIC_HOUSE = 3;

    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'rubrics';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['rubrics_name'], 'required'],
            [['rubrics_name'], 'string', 'max' => 170],
        ];
    }
    
    /**
     * Связь с таблицей Новости
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['news_type_rubric_id' => 'rubrics_id']);
    }
    
    public static function getArrayRubrics(){
        $list = self::find()
                ->asArray()
                ->all();
        
        return ArrayHelper::map($list, 'rubrics_id', 'rubrics_name');
    }

    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'rubrics_id' => 'Rubrics ID',
            'rubrics_name' => 'Rubrics Name',
        ];
    }

}
