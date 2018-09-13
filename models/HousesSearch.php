<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Houses;

/**
 * HousesSearch represents the model behind the search form of `app\models\Houses`.
 */
class HousesSearch extends Houses
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['houses_id', 'houses_porch', 'houses_floor', 'houses_flat', 'houses_rooms', 'houses_square', 'houses_client_id'], 'integer'],
            [['houses_name', 'houses_town', 'houses_street', 'houses_number_house'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Houses::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'houses_id' => $this->houses_id,
            'houses_porch' => $this->houses_porch,
            'houses_floor' => $this->houses_floor,
            'houses_flat' => $this->houses_flat,
            'houses_rooms' => $this->houses_rooms,
            'houses_square' => $this->houses_square,
            'houses_client_id' => $this->houses_client_id,
        ]);

        $query->andFilterWhere(['like', 'houses_name', $this->houses_name])
            ->andFilterWhere(['like', 'houses_town', $this->houses_town])
            ->andFilterWhere(['like', 'houses_street', $this->houses_street])
            ->andFilterWhere(['like', 'houses_number_house', $this->houses_number_house]);

        return $dataProvider;
    }
}
