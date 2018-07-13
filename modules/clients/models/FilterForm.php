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
    public function searchRequest($type_id) {
        
        $query = Requests::find()->orderBy(['created_at' => SORT_DESC]);
        
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
                'query' => $query->andWhere(['requests_type_id' => $type_id]),
                'pagination' => [
                    'forcePageParam' => false,
                    'pageSizeParam' => false,
                    'pageSize' => 15,
                ],
            ]);
        }

        $this->load($type_id);

        if (!$this->validate()) {
            return $query;
        }

        return $dataProvider;
        
    }
}
