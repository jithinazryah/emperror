<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DeliveryOrder;

/**
 * DeliveryOrderSearch represents the model behind the search form about `common\models\DeliveryOrder`.
 */
class DeliveryOrderSearch extends DeliveryOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'CB', 'UB'], 'integer'],
            [['to', 'ref_no', 'date', 'name', 'po_box', 'arrived_from', 'arrived_on', 'vessel_name', 'voyage_no', 'DOC', 'DOU'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = DeliveryOrder::find();

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
            'id' => $this->id,
            'date' => $this->date,
            'arrived_on' => $this->arrived_on,
            'status' => $this->status,
            'CB' => $this->CB,
            'UB' => $this->UB,
            'DOC' => $this->DOC,
            'DOU' => $this->DOU,
        ]);

        $query->andFilterWhere(['like', 'to', $this->to])
            ->andFilterWhere(['like', 'ref_no', $this->ref_no])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'po_box', $this->po_box])
            ->andFilterWhere(['like', 'arrived_from', $this->arrived_from])
            ->andFilterWhere(['like', 'vessel_name', $this->vessel_name])
            ->andFilterWhere(['like', 'voyage_no', $this->voyage_no]);

        return $dataProvider;
    }
}
