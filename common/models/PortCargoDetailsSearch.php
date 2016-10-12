<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PortCargoDetails;

/**
 * PortCargoDetailsSearch represents the model behind the search form about `common\models\PortCargoDetails`.
 */
class PortCargoDetailsSearch extends PortCargoDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'appointment_id', 'port_call_id', 'CB', 'UB'], 'integer'],
            [['cargo_type', 'loaded_quantity', 'bl_quantity', 'remarks', 'stoppages_delays', 'cargo_document', 'masters_comment', 'DOC', 'DOU'], 'safe'],
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
        $query = PortCargoDetails::find();

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
            'port_call_id' => $this->port_call_id,
            'CB' => $this->CB,
            'UB' => $this->UB,
            'DOC' => $this->DOC,
            'DOU' => $this->DOU,
        ]);

        $query->andFilterWhere(['like', 'cargo_type', $this->cargo_type])
            ->andFilterWhere(['like', 'loaded_quantity', $this->loaded_quantity])
            ->andFilterWhere(['like', 'bl_quantity', $this->bl_quantity])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'stoppages_delays', $this->stoppages_delays])
            ->andFilterWhere(['like', 'cargo_document', $this->cargo_document])
            ->andFilterWhere(['like', 'masters_comment', $this->masters_comment]);

        return $dataProvider;
    }
}
