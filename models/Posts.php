<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Employees;

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
            [['post_name'], 'required'],
            [['post_name'], 'string', 'max' => 100],
            [['p_description'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * Связь с таблицей сотрудники
     */
    public function getEmployees()
    {
        return $this->hasMany(Employees::className(), ['employee_posts_id' => 'post_id']);
    }    

    /**
     * Метки полей
     */
    public function attributeLabels()
    {
        return [
            'post_id' => 'Post ID',
            'post_name' => 'Post Name',
            'p_description' => 'P Description',
        ];
    }

}
