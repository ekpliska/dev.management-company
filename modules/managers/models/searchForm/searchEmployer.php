<?php

    namespace app\modules\managers\models\searchForm;
    use yii\base\Model;

/**
 * Поиск по сотрудникам
 */
class searchEmployer extends Model {
    
    public $_input;
    
    public function rules() {
        return [
            ['_input', 'string', 'min' => 1, 'max' => '70'],
            ['_input', 'filter', 'filter' => 'trim'],
            ['_input', 
                'match',
                'pattern' => '/^[A-Za-zА-Яа-яЁё0-9\_\-\ ]+$/iu',
                'message' => 'Вы используете запрещенный набор символов',
            ],
        ];
    }
    
    public function attributeLabels() {
        return [
            '_input' => 'Поиск',
        ];
    }
    
}
