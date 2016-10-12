<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EstimatedProforma;

/**
 * EstimatedProformaSearch represents the model behind the search form about `common\models\EstimatedProforma`.
 */
class EstimatedProformaSearch extends EstimatedProforma
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'apponitment_id', 'service_id', 'supplier', 'currency', 'epda', 'principal', 'invoice_type', 'status', ], 'integer'],
            [['unit_rate', 'unit', 'roe', 'comments', 'DOC', 'DOU'], 'safe'],
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
        $query = EstimatedProforma::find()->groupBy(['apponitment_id']);

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
            'apponitment_id' => $this->apponitment_id,
            'service_id' => $this->service_id,
            'supplier' => $this->supplier,
            'currency' => $this->currency,
            'epda' => $this->epda,
            'principal' => $this->principal,
            'invoice_type' => $this->invoice_type,
            'status' => $this->status,
            'CB' => $this->CB,
            'UB' => $this->UB,
            'DOC' => $this->DOC,
            'DOU' => $this->DOU,
        ]);

        $query->andFilterWhere(['like', 'unit_rate', $this->unit_rate])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'roe', $this->roe])
            ->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}
