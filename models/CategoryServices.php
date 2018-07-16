<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

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
            [['category_name'], 'string', 'max' => 255],
        ];
    }
    
    public function getService() {
        return $this->hasMany(Services::className(), ['services_category_id' => 'category_id']);
    }
    
    public static function getAllCategory() {
        return self::find()
                ->joinWith(['service'])
                ->andWhere(['services.isPay' => 1])
                ->all();
    }

    /**
     * Массив статусов заявок
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'category_name' => 'Category Name',
        ];
    }
}
