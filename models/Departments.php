<?php

    namespace app\models;
    use Yii;
    use yii\helpers\ArrayHelper;
    use yii\db\ActiveRecord;
    use app\models\Employees;

/**
 * Отделение, подразделение (сотрудники)
 */
class Departments extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'departments';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['department_name'], 'required'],
            [['department_name'], 'string', 'max' => 100],
            [['d_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * Связь с таблицей сотрудники
     */
    public function getEmployees()
    {
        return $this->hasMany(Employees::className(), ['employee_department_id' => 'department_id']);
    }
    
    public static function getArrayDepartments() {
        $array = self::find()->asArray()->all();
        return ArrayHelper::map($array, 'department_id', 'department_name');
    }    
    
    /**
     * Метки полей
     */
    public function attributeLabels() {
        return [
            'department_id' => 'Department ID',
            'department_name' => 'Department Name',
            'd_description' => 'D Description',
        ];
    }

}
