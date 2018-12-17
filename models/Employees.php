<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Posts;
    use app\models\Departments;

/**
 * Сотрудники
 */
class Employees extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'employees';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['employee_name', 'employee_surname', 'employee_second_name', 'employee_birthday', 'employee_department_id', 'employee_posts_id'], 'required'],
            [['employee_department_id', 'employee_posts_id'], 'integer'],
            [['employee_name', 'employee_surname', 'employee_second_name', 'employee_birthday'], 'string', 'max' => 70],
            [['employee_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departments::className(), 'targetAttribute' => ['employee_department_id' => 'department_id']],
            [['employee_posts_id'], 'exist', 'skipOnError' => true, 'targetClass' => Posts::className(), 'targetAttribute' => ['employee_posts_id' => 'post_id']],
        ];
    }
    
    /**
     * Свзяь с таблице Подразделения
     */
    public function getEmployeeDepartment()
    {
        return $this->hasOne(Departments::className(), ['department_id' => 'employee_department_id']);
    }

    /**
     * Свзяь с таблицей Долдности
     */
    public function getEmployeePosts()
    {
        return $this->hasOne(Posts::className(), ['post_id' => 'employee_posts_id']);
    }    

    public static function findByID($employer_id) {
        return self::find()
                ->andWhere(['employee_id' => $employer_id])
                ->one();
    }
    
    public function getId() {
        return $this->employee_id;
    }
    
    /*
     * Получить полное имя
     * Фамилия Имя Отчества Сотрудника
     */
    public function getFullName() {
        return $this->employee_surname . ' ' 
                . $this->employee_name . ' '
                . $this->employee_second_name;
    }
    
    /*
     * Получить все зявки Специалиста
     */
    public function getRequests() {
        return Requests::find()
                ->andWhere(['requests_specialist_id' => $this->employee_id])
                ->andWhere(['!=', 'status', StatusRequest::STATUS_CLOSE])
                ->asArray()
                ->all();
    }

    /*
     * Получить все заявки на платные услуги Специалиста
     */
    public function getPaidServices() {
        return PaidServices::find()
                ->andWhere(['services_specialist_id' => $this->employee_id])
                ->andWhere(['!=', 'status', StatusRequest::STATUS_CLOSE])
                ->asArray()
                ->all();
    }    
    
    /**
     * Метки полей
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee ID',
            'employee_name' => 'Employee Name',
            'employee_surname' => 'Employee Surname',
            'employee_second_name' => 'Employee Second Name',
            'employee_birthday' => 'Employee Birthday',
            'employee_department_id' => 'Employee Department ID',
            'employee_posts_id' => 'Employee Posts ID',
        ];
    }

}
