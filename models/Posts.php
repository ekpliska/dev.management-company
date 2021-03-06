<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;

/**
 * Должности сотрудников
 */
class Posts extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'posts';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['post_name', 'posts_department_id'], 'required'],
            [['post_name'], 'string', 'max' => 100],
            ['posts_department_id', 'integer'],
        ];
    }
    
    /**
     * Связь с таблицей сотрудники
     */
    public function getEmployees() {
        return $this->hasMany(Employees::className(), ['employee_posts_id' => 'post_id']);
    }    
    
    /*
     * Список всех должностей
     */
    public static function getArrayPosts() {
        $array = self::find()->asArray()->all();
        return ArrayHelper::map($array, 'post_id', 'post_name');
    }

    /**
     * Метки полей
     */
    public function attributeLabels()
    {
        return [
            'post_id' => 'Post ID',
            'post_name' => 'Должность',
            'posts_department_id' => 'Подразделение',
        ];
    }

}
