<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GenerateInvoice;

/**
 * GenerateInvoiceSearch represents the model behind the search form about `common\models\GenerateInvoice`.
 */
class GenerateInvoiceSearch extends GenerateInvoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'invoice', 'on_account_of', 'job', 'payment_terms', 'status', 'CB', 'UB'], 'integer'],
            [['to_address', 'invoice_number', 'date', 'oops_id', 'doc_no', 'DOC', 'DOU'], 'safe'],
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
        $query = GenerateInvoice::find();

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
            'invoice' => $this->invoice,
            'date' => $this->date,
            'on_account_of' => $this->on_account_of,
            'job' => $this->job,
            'payment_terms' => $this->payment_terms,
            'status' => $this->status,
            'CB' => $this->CB,
            'UB' => $this->UB,
            'DOC' => $this->DOC,
            'DOU' => $this->DOU,
        ]);

        $query->andFilterWhere(['like', 'to_address', $this->to_address])
            ->andFilterWhere(['like', 'invoice_number', $this->invoice_number])
            ->andFilterWhere(['like', 'oops_id', $this->oops_id])
            ->andFilterWhere(['like', 'doc_no', $this->doc_no]);

        return $dataProvider;
    }
}
