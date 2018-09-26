<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\User;
    use app\models\Departments;
    use app\models\Posts;

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
            
            [['employers_name', 'employers_surname', 'employers_second_name'], 'filter', 'filter' => 'trim'],

            [['employers_name', 'employers_surname', 'employers_second_name'], 'string', 'min' => 3, 'max' => 70],
            
            [
                ['employers_name', 'employers_surname', 'employers_second_name'], 
                'match',
                'pattern' => '/^[А-Яа-я\ \-]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы русского алфавита, и знак "-"',
            ],
            
            [['employers_department_id', 'employers_posts_id'], 'integer'],
            
            ['employers_birthday', 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /*
     * Свзяь с таблицей Пользователи
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['user_employer_id' => 'employers_id']);
    }    

    /*
     * Свзяь с таблицей Подразделения
     */
    public function getDepartment() {
        return $this->hasOne(Departments::className(), ['departments_id' => 'employers_department_id']);
    }

    /*
     * Свзяь с таблицей Должности
     */
    public function getPost() {
        return $this->hasOne(Posts::className(), ['posts_id' => 'employers_posts_id']);
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
            'employers_posts_id' => 'Должность',
            'employers_birthday' => 'Дата рождения',
        ];
    }
}
