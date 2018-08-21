<?php
    
    namespace app\modules\clients\models;
    use yii\base\Model;
    use app\models\PersonalAccount;
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
     * Фильтр заявок по типу заявки
     */
    public function searchRequest($type_id, $account_id) {
        
        $query = Requests::find()
                ->andWhere(['requests_account_id' => $account_id])
                ->orderBy(['created_at' => SORT_DESC]);
        
        if (empty($type_id)) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'forcePageParam' => false,
                    'pageSizeParam' => false,
                    'pageSize' => 15,
                ],
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => $query
                    ->andWhere(['requests_type_id' => $type_id])
                    ->andWhere(['requests_account_id' => $account_id]),
                'pagination' => [
                    'forcePageParam' => false,
                    'pageSizeParam' => false,
                    'pageSize' => 15,
                ],
            ]);
        }

        $this->load($type_id, $account_id);

        if (!$this->validate()) {
            return $query;
        }

        return $dataProvider;
        
    }

    /*
     * Фильтр заявок по статусу
     */
    public function searchStatusRequest($account_id, $status) {
        
        $query = Requests::find()
                ->andWhere(['requests_account_id' => $account_id])
                ->orderBy(['created_at' => SORT_DESC]);
        
        if ($status == -1) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'forcePageParam' => false,
                    'pageSizeParam' => false,
                    'pageSize' => 15,
                ],
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => $query
                    ->andWhere(['status' => $status])
                    ->andWhere(['requests_account_id' => $account_id]),
                'pagination' => [
                    'forcePageParam' => false,
                    'pageSizeParam' => false,
                    'pageSize' => 15,
                ],
            ]);
        }

        $this->load($account_id, $status);

        if (!$this->validate()) {
            return $query;
        }

        return $dataProvider;
        
    }

    
}
