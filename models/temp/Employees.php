<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Requests;

class Employees extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employees_name', 'employees_surname', 'employees_second_name', 'employees_department_id', 'employees_posts_id', 'employees_birthday'], 'required'],
            [['employees_department_id', 'employees_posts_id'], 'integer'],
            [['employees_birthday'], 'safe'],
            [['employees_name', 'employees_surname', 'employees_second_name'], 'string', 'max' => 70],
        ];
    }
    
    
    public static function findByID($employer_id) {
        return self::find()
                ->andWhere(['employee_id' => $employer_id])
                ->one();
    }
    
    public function getId() {
        return $this->employees_id;
    }
    
    /*
     * Получить полное имя
     * Фамилия Имя Отчества Сотрудника
     */
    public function getFullName() {
        return $this->employees_surname . ' ' 
                . $this->employees_name . ' '
                . $this->employees_second_name;
    }
    
    /*
     * Получить все зявки Специалиста
     */
    public function getRequests() {
        return Requests::find()
                ->andWhere(['requests_specialist_id' => $this->employees_id])
                ->andWhere(['!=', 'status', StatusRequest::STATUS_CLOSE])
                ->asArray()
                ->all();
    }

    /*
     * Получить все заявки на платные услуги Специалиста
     */
    public function getPaidServices() {
        return PaidServices::find()
                ->andWhere(['services_specialist_id' => $this->employees_id])
                ->andWhere(['!=', 'status', StatusRequest::STATUS_CLOSE])
                ->asArray()
                ->all();
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employees_id' => 'Employers ID',
            'employees_name' => 'Имя',
            'employees_surname' => 'Фамилия',
            'employees_second_name' => 'Отчество',
            'employees_department_id' => 'Подразделение',
            'employees_posts_id' => 'Должность (Специальность)',
            'employees_birthday' => 'Дата рождения',
        ];
    }
}
