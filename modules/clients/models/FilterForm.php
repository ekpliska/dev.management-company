<?php
    
    namespace app\modules\clients\models;
    use yii\base\Model;
    use app\models\PersonalAccount;
    use app\models\Requests;

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
            $dataProvider = new \yii\data\ActiveDataProvider([
                'query' => $query,
            ]);
        } else {
            $dataProvider = new \yii\data\ActiveDataProvider([
                'query' => $query->andWhere(['requests_type_id' => $type_id]),
            ]);
        }

        $this->load($type_id);

        if (!$this->validate()) {
            // $query->andWhere(['requests_type_id' => $type_id]);
            return $query;
        }

        return $dataProvider;
        
    }
}
