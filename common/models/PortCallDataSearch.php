<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PortCallData;

/**
 * PortCallDataSearch represents the model behind the search form about `common\models\PortCallData`.
 */
class PortCallDataSearch extends PortCallData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'appointment_id', 'additional_info', 'status', 'CB', 'UB'], 'integer'],
            [['eta', 'ets', 'eosp', 'arrived_anchorage', 'nor_tendered', 'dropped_anchor', 'anchor_aweigh', 'arrived_pilot_station', 'pob_inbound', 'first_line_ashore', 'all_fast', 'gangway_down', 'agent_on_board', 'immigration_commenced', 'immigartion_completed', 'cargo_commenced', 'cargo_completed', 'pob_outbound', 'lastline_away', 'cleared_channel', 'cosp', 'fasop', 'eta_next_port', 'comments', 'DOC', 'DOU','documentation_completed'], 'safe'],
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
        $query = PortCallData::find();

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
            'eta' => $this->eta,
            'ets' => $this->ets,
            'eosp' => $this->eosp,
            'arrived_anchorage' => $this->arrived_anchorage,
            'nor_tendered' => $this->nor_tendered,
            'dropped_anchor' => $this->dropped_anchor,
            'anchor_aweigh' => $this->anchor_aweigh,
            'arrived_pilot_station' => $this->arrived_pilot_station,
            'pob_inbound' => $this->pob_inbound,
            'first_line_ashore' => $this->first_line_ashore,
            'all_fast' => $this->all_fast,
            'gangway_down' => $this->gangway_down,
            'agent_on_board' => $this->agent_on_board,
            'immigration_commenced' => $this->immigration_commenced,
            'immigartion_completed' => $this->immigartion_completed,
            'cargo_commenced' => $this->cargo_commenced,
            'cargo_completed' => $this->cargo_completed,
            'pob_outbound' => $this->pob_outbound,
            'lastline_away' => $this->lastline_away,
            'cleared_channel' => $this->cleared_channel,
            'cosp' => $this->cosp,
            'fasop' => $this->fasop,
            'eta_next_port' => $this->eta_next_port,
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
