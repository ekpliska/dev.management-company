<?php

    namespace app\models;
    use Yii;

class Employers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employers_name', 'employers_surname', 'employers_second_name', 'employers_department_id', 'employers_posts_id', 'employers_birthday'], 'required'],
            [['employers_department_id', 'employers_posts_id'], 'integer'],
            [['employers_birthday'], 'safe'],
            [['employers_name', 'employers_surname', 'employers_second_name'], 'string', 'max' => 70],
        ];
    }
    
    
    public static function findByID($employer_id) {
        return self::find()
                ->andWhere(['employers_id' => $employer_id])
                ->one();
    }
    
    public function getId() {
        return $this->employers_id;
    }
    
    /*
     * Получить полное имя
     * Фамилия Имя Отчества Сотрудника
     */
    public function getFullName() {
        return $this->employers_surname . ' ' 
                . $this->employers_name . ' '
                . $this->employers_second_name;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employers_id' => 'Employers ID',
            'employers_name' => 'Employers Name',
            'employers_surname' => 'Employers Surname',
            'employers_second_name' => 'Employers Second Name',
            'employers_department_id' => 'Employers Department ID',
            'employers_posts_id' => 'Employers Posts ID',
            'employers_birthday' => 'Employers Birthday',
        ];
    }
}
