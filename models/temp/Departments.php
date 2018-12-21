<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use app\models\Posts;

/**
 * Подразделения
 */
class Departments extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'departments';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['department_name'], 'string', 'max' => 150],
        ];
    }

    /*
     * Связь с таблицей Должности
     */
    public function getPost() {
        return $this->hasMany(Posts::className(), ['posts_department_id' => 'department_id']);
    }    
    
    public static function getArrayDepartments() {
        $array = self::find()->asArray()->all();
        return ArrayHelper::map($array, 'department_id', 'department_name');
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'department_id' => 'Departments ID',
            'department_name' => 'Departments Name',
        ];
    }
}
