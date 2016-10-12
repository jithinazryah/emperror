<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PortCallDataRob;

/**
 * PortCallDataRobSearch represents the model behind the search form about `common\models\PortCallDataRob`.
 */
class PortCallDataRobSearch extends PortCallDataRob
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'appointment_id', 'fo_arrival_unit', 'fo_arrival_quantity', 'do_arrival_unit', 'do_arrival_quantity', 'go_arrival_unit', 'go_arrival_quantity', 'lo_arrival_unit', 'lo_arrival_quantity', 'fresh_water_arrival_unit', 'fresh_water_arrival_quantity', 'fo_sailing_unit', 'fo_sailing_quantity', 'do_sailing_unit', 'do_sailing_quantity', 'go_sailing_unit', 'go_sailing_quantity', 'lo_sailing_unit', 'lo_sailing_quantity', 'fresh_water_sailing_unit', 'fresh_water_sailing_quantity', 'additional_info', 'status', 'CB', 'UB'], 'integer'],
            [['comments', 'DOC', 'DOU'], 'safe'],
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
        $query = PortCallDataRob::find();

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
            'fo_arrival_unit' => $this->fo_arrival_unit,
            'fo_arrival_quantity' => $this->fo_arrival_quantity,
            'do_arrival_unit' => $this->do_arrival_unit,
            'do_arrival_quantity' => $this->do_arrival_quantity,
            'go_arrival_unit' => $this->go_arrival_unit,
            'go_arrival_quantity' => $this->go_arrival_quantity,
            'lo_arrival_unit' => $this->lo_arrival_unit,
            'lo_arrival_quantity' => $this->lo_arrival_quantity,
            'fresh_water_arrival_unit' => $this->fresh_water_arrival_unit,
            'fresh_water_arrival_quantity' => $this->fresh_water_arrival_quantity,
            'fo_sailing_unit' => $this->fo_sailing_unit,
            'fo_sailing_quantity' => $this->fo_sailing_quantity,
            'do_sailing_unit' => $this->do_sailing_unit,
            'do_sailing_quantity' => $this->do_sailing_quantity,
            'go_sailing_unit' => $this->go_sailing_unit,
            'go_sailing_quantity' => $this->go_sailing_quantity,
            'lo_sailing_unit' => $this->lo_sailing_unit,
            'lo_sailing_quantity' => $this->lo_sailing_quantity,
            'fresh_water_sailing_unit' => $this->fresh_water_sailing_unit,
            'fresh_water_sailing_quantity' => $this->fresh_water_sailing_quantity,
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
