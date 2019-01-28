<?php

    namespace app\modules\clients\models\_searchForm;
    use Yii;
    use yii\base\Model;

/*
 * Поиск по специалисту/исполнителю
 */
class searchInPaidServices extends Model {
    
    public $_input;

    /*
     * Правила валидации
     */
    public function rules() {
        
        return [
            ['_input', 'filter', 'filter' => 'trim'],
            /* ['_input', 'string', 'min' => 3, 'max' => 70],
            ['_input', 
                'match',
                'pattern' => '/^[А-Яа-яёЁ\s,]+$/u',
                'message' => 'Данное поле может содержать только буквы русского алфавита',
            ],
             */
        ];
        
    }
    
    /*
     * Поиск по исполнителю
     */
    public function search($value, $account_id) {
        
        $query = (new \yii\db\Query())
                ->select('p.services_number, '
                        . 'c.category_name, '
                        . 's.service_name, '
                        . 'p.created_at, p.services_comment, '
                        . 'e.employee_surname, e.employee_name, e.employee_second_name, '
                        . 'p.status,'
                        . 'p.updated_at')
                ->from('paid_services as p')
                ->join('LEFT JOIN', 'services as s', 's.service_id = p.services_name_services_id')
                ->join('LEFT JOIN', 'category_services as c', 'c.category_id = p.services_servise_category_id')
                ->join('LEFT JOIN', 'employees as e', 'e.employee_id = p.services_specialist_id')
                ->andWhere(['services_account_id' => $account_id])
                ->orderBy(['created_at' => SORT_DESC]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($value, $account_id);
        
        $query->andFilterWhere(['like', 'e.employee_surname', $value]);
        $query->orFilterWhere(['like', 'p.services_number', $value]);
        
        return $dataProvider;
        
    }
    
    /*
     * Метки для полей формы
     */
    public function attributeLabels() {
        return [
            '_input' => 'Фамилия исполнителя',
        ];
    }
    
}
