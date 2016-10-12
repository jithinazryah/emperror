<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PortCallDataDraft;

/**
 * PortCallDataDraftSearch represents the model behind the search form about `common\models\PortCallDataDraft`.
 */
class PortCallDataDraftSearch extends PortCallDataDraft
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'appointment_id', 'data_id', 'fwd_arrival_unit', 'fwd_arrival_quantity', 'aft_arrival_unit', 'aft_arrival_quantity', 'mean_arrival_unit', 'mean_arrival_quantity', 'fwd_sailing_unit', 'fwd_sailing_quantity', 'aft_sailing_unit', 'aft_sailing_quantity', 'mean_sailing_unit', 'mean_sailing_quantity', 'additional_info', 'status', 'CB', 'UB'], 'integer'],
            [['intial_survey_commenced', 'intial_survey_completed', 'finial_survey_commenced', 'finial_survey_completed', 'comments', 'DOC', 'DOU'], 'safe'],
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
        $query = PortCallDataDraft::find();

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
            'data_id' => $this->data_id,
            'intial_survey_commenced' => $this->intial_survey_commenced,
            'intial_survey_completed' => $this->intial_survey_completed,
            'finial_survey_commenced' => $this->finial_survey_commenced,
            'finial_survey_completed' => $this->finial_survey_completed,
            'fwd_arrival_unit' => $this->fwd_arrival_unit,
            'fwd_arrival_quantity' => $this->fwd_arrival_quantity,
            'aft_arrival_unit' => $this->aft_arrival_unit,
            'aft_arrival_quantity' => $this->aft_arrival_quantity,
            'mean_arrival_unit' => $this->mean_arrival_unit,
            'mean_arrival_quantity' => $this->mean_arrival_quantity,
            'fwd_sailing_unit' => $this->fwd_sailing_unit,
            'fwd_sailing_quantity' => $this->fwd_sailing_quantity,
            'aft_sailing_unit' => $this->aft_sailing_unit,
            'aft_sailing_quantity' => $this->aft_sailing_quantity,
            'mean_sailing_unit' => $this->mean_sailing_unit,
            'mean_sailing_quantity' => $this->mean_sailing_quantity,
            'additional_info' => $this->additional_info,
            'status' => $this->status,
            'CB' => $this->CB,
            'UB' => $this->UB,
            'DOC' => $this->DOC,
            'DOU' => $this->DOU,
        ]);

        $query->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}
