<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use app\models\Services;

/**
 * Категории услуг
 */
class CategoryServices extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'category_services';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            ['category_name', 'required', 'message' => 'Поле обязательно для заполнения'],
            [['category_name'], 'string', 'min' => '3', 'max' => 100],
            ['category_name', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'category_name',
                'message' => 'Указанная категория существует',
            ],
        ];
    }
    
    /*
     * Связь с таблице Услуги
     */
    public function getService() {
        return $this->hasMany(Services::className(), ['service_category_id' => 'category_id']);
    }
    
    /*
     * Формирование категорий заявок
     */
    public static function getCategoryNameArray() {
        
        $array = static::find()
                ->orderBy(['category_name' => SORT_ASC])
                ->asArray()
                ->all();
        
        return ArrayHelper::map($array, 'category_id', 'category_name');
    }
    
    /*
     * Получить название категории по ID
     */
    public static function getNameCategory($category_id) {
        
        $name = self::find()
                ->where(['category_id' => $category_id])
                ->asArray()
                ->one();
        return $name ? $name : null;
    }
    /**
     * Массив статусов заявок
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'ID',
            'category_name' => 'Название категории',
        ];
    }
}
