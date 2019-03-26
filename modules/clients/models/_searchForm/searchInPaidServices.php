<?php

    namespace app\modules\clients\models\_searchForm;
    use Yii;
    use yii\base\Model;
    use yii\helpers\HtmlPurifier;

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
            ['_input', 'string', 'min' => 3, 'max' => 70],
        ];
        
    }
    
    /*
     * Поиск по исполнителю
     */
    public function search($value, $account_id) {
        
        $query = (new \yii\db\Query())
                ->select('p.services_number, '
                        . 'p.services_id, '
                        . 'p.status, '
                        . 'p.grade, '
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
                ->where(['services_account_id' => $account_id])
                ->orderBy(['created_at' => SORT_DESC]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => (Yii::$app->params['countRec']['client']) ? Yii::$app->params['countRec']['client'] : 15,
            ],
        ]);
        
        $this->load($value, $account_id);
        
        $query->andFilterWhere(['like', 'employee_surname', HtmlPurifier::process($value)]);
        $query->orFilterWhere(['=', 'services_number', HtmlPurifier::process($value)]);
        
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
