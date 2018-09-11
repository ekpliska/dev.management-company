<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Employers;

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
            [['departments_name'], 'string', 'max' => 150],
        ];
    }
    
    /*
     * Свзяь с таблицей Сотрудники
     */
    public function getDepartment() {
        return $this->hasMany(Employers::className(), ['employers_department_id' => 'departments_id']);
    }     

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'departments_id' => 'Departments ID',
            'departments_name' => 'Departments Name',
        ];
    }
}
