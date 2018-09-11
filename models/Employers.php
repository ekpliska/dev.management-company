<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\User;
    use app\models\Departments;

/**
 * Сотрудники
 */
class Employers extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName() {
        return 'employers';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['employers_department_id', 'isMale', 'isFemale'], 'integer'],
            [['employers_name', 'employers_surname', 'employers_second_name'], 'string', 'max' => 70],
        ];
    }

    /*
     * Свзяь с таблицей Пользователи
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['user_employee_id' => 'employers_id']);
    }    

    /*
     * Свзяь с таблицей Подразделения
     */
    public function getDepartment() {
        return $this->hasOne(Departments::className(), ['departments_id' => 'employers_department_id']);
    }    
    
    /**
     * Метки для полей
     */
    public function attributeLabels() {
        return [
            'employers_id' => 'Employers ID',
            'employers_name' => 'Имф',
            'employers_surname' => 'Фамилия',
            'employers_second_name' => 'Отчество',
            'employers_department_id' => 'Подразделение',
            'isMale' => 'Мужской пол',
            'isFemale' => 'Женский пол',
        ];
    }
}
