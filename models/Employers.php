<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use app\models\User;
    use app\models\Departments;
    use app\models\Posts;

/**
 * Сотрудники
 */
class Employers extends ActiveRecord
{
    
    const GENDER_MALE = 0;
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
            
            [[
                'employers_department_id', 'employers_posts_id', 'employers_gender',
                'employers_name', 'employers_surname', 'employers_second_name'], 'required'],
            
            [['employers_name', 'employers_surname', 'employers_second_name'], 'filter', 'filter' => 'trim'],

            [['employers_name', 'employers_surname', 'employers_second_name'], 'string', 'min' => 3, 'max' => 70],
            
            [
                ['employers_name', 'employers_surname', 'employers_second_name'], 
                'match',
                'pattern' => '/^[А-Яа-я\ \-]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы русского алфавита, и знак "-"',
            ],
            
            [['employers_department_id', 'employers_posts_id', 'employers_gender'], 'integer'],
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

    /*
     * Свзяь с таблицей Должности
     */
    public function getPost() {
        return $this->hasOne(Posts::className(), ['posts_id' => 'employers_posts_id']);
    }    
    
    /*
     * Сформировать массив
     */
    public static function getGenderArray() {
        return [
            ['id' => self::GENDER_MALE, 'name' => 'Мужской'],
            ['id' => self::GENDER_FEMALE, 'name' => 'Женский'],
        ];
    }
    
    /*
     * Выводим текстовое обознаечние пола
     */
    public function getGenderName() {
        return ArrayHelper::getValue(self::getGenderArray(), $this->gender);
    }
    
    /*
     * После создания нового записи нового сотрудника
     * 
     * Создаем для него учетную запись
     */
//    public function afterSave($insert, $changedAttributes) {
//        parent::afterSave($insert, $changedAttributes);
//        
//        if ($insert) {
//            $new_user = new User();
//            echo '<pre>';
//            var_dump($insert);
//            echo '<pre>';
//            var_dump($changedAttributes);
//            die();
//        }
//    }
    
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
            'employers_gender' => 'Пол',
        ];
    }
}
