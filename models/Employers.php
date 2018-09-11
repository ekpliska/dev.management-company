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
    // Мужской
    const GENDER_MALE = 0;
    // Женский
    const GENDER_FEMALE = 1;
    
    
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
            [['employers_department_id', 'employers_gender'], 'integer'],
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
    
    public static function getGenderArray() {
        return [
            self::GENDER_MALE => 'Мужской',
            self::GENDER_FEMALE => 'Женский',
        ];
    }
    
    public function getGenderName() {
        return \yii\helpers\ArrayHelper::getValue(self::getGenderArray(), $this->gender);
    }
    
    /**
     * Метки для полей
     */
    public function attributeLabels() {
        return [
            'employers_id' => 'Employers ID',
            'employers_name' => 'Имя',
            'employers_surname' => 'Фамилия',
            'employers_second_name' => 'Отчество',
            'employers_department_id' => 'Подразделение',
            'employers_gender' => 'Пол',
        ];
    }
}
