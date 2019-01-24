<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;

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
            ['category_name', 'required'],
            [['category_name'], 'string', 'min' => '3', 'max' => 100],
            ['category_name', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'category_name',
                'message' => 'Указанная категория существует',
            ],
            [['category_name'],
                'match', 
                'pattern' => '/^[А-Яа-я\0-9\ \_\-]+$/iu', 
                'message' => 'Вы используете запрещенные символы',
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
