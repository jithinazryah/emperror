<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SupplierFunding;

/**
 * SupplierFundingSearch represents the model behind the search form about `common\models\SupplierFunding`.
 */
class SupplierFundingSearch extends SupplierFunding
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'appointment_id', 'close_estimate_id', 'service_id', 'supplier', 'status', 'CB', 'UB'], 'integer'],
            [['actual_amount', 'amount_debit', 'balance_amount'], 'number'],
            [['DOC', 'DOU'], 'safe'],
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
        $query = SupplierFunding::find();

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
            'appointment_id' => $this->appointment_id,
            'close_estimate_id' => $this->close_estimate_id,
            'service_id' => $this->service_id,
            'supplier' => $this->supplier,
            'actual_amount' => $this->actual_amount,
            'amount_debit' => $this->amount_debit,
            'balance_amount' => $this->balance_amount,
            'status' => $this->status,
            'CB' => $this->CB,
            'UB' => $this->UB,
            'DOC' => $this->DOC,
            'DOU' => $this->DOU,
        ]);

        return $dataProvider;
    }
}
