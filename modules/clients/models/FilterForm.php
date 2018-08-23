<?php
    
    namespace app\modules\clients\models;
    use yii\base\Model;
    use app\models\Requests;
    use yii\data\ActiveDataProvider;

/**
 * Форма фильтрации для страницы Лицевой счет / Общая информация
 */
class FilterForm extends Model {
    
    public $_value;
    
    public function filerRequest($type_id) {
        
        $query = Requests::find()
                ->andWhere(['requests_type_id' => $type_id])
                ->all();
        return $query;
    }
    
    /*
     * Фильтр заявок 
     * @param integer $type_id по лицевому счету
     * @param integer $account_id по типу заявки 
     * @param integer $status по статусу
     */
    public function searchRequest($type_id, $account_id, $status) {
        
        $query = Requests::find()
                ->andWhere(['requests_account_id' => $account_id])
                ->orderBy(['created_at' => SORT_DESC]);
        
        
        if (empty($type_id) && $status == -1) {
            // Тип = Все и Статус = Все
            $value_query = $query;
        } else 
            if (empty($type_id) && $status !== -1) {
                // Тип = Все и Статус = Выбор
                $value_query = $query->andWhere(['status' => $status]);
            } else 
                if ($type_id && $status == -1) {
                    // Тип = Выбор и Статус = Все
                    $value_query = $query->andWhere(['requests_type_id' => $type_id]);
            } else {
                $value_query = $query
                        ->andWhere(['requests_type_id' => $type_id])
                        ->andWhere(['status' => $status]);
            }
            
        $this->load($type_id, $account_id, $status);

        if (!$this->validate()) {
            return $query;
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $value_query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => Yii::$app->params['countRec']['client'] ? Yii::$app->params['countRec']['client'] : 15,
            ],
        ]);

        return $dataProvider;
        
    }
    
}
