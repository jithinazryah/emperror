<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ImigrationClearance;

/**
 * ImigrationClearanceSearch represents the model behind the search form about `common\models\ImigrationClearance`.
 */
class ImigrationClearanceSearch extends ImigrationClearance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'appointment_id', 'status', 'CB', 'UB'], 'integer'],
            [['arrived_ps', 'pob_inbound', 'first_line_ashore', 'all_fast', 'agent_on_board', 'imi_clearence_commenced', 'imi_clearence_completed', 'pob_outbound', 'cast_off', 'last_line_away', 'cleared_break_water', 'drop_anchor', 'heave_up_anchor', 'pilot_boarded', 'DOC', 'DOU'], 'safe'],
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
        $query = ImigrationClearance::find();

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
            'arrived_ps' => $this->arrived_ps,
            'pob_inbound' => $this->pob_inbound,
            'first_line_ashore' => $this->first_line_ashore,
            'all_fast' => $this->all_fast,
            'agent_on_board' => $this->agent_on_board,
            'imi_clearence_commenced' => $this->imi_clearence_commenced,
            'imi_clearence_completed' => $this->imi_clearence_completed,
            'pob_outbound' => $this->pob_outbound,
            'cast_off' => $this->cast_off,
            'last_line_away' => $this->last_line_away,
            'cleared_break_water' => $this->cleared_break_water,
            'drop_anchor' => $this->drop_anchor,
            'heave_up_anchor' => $this->heave_up_anchor,
            'pilot_boarded' => $this->pilot_boarded,
            'status' => $this->status,
            'CB' => $this->CB,
            'UB' => $this->UB,
            'DOC' => $this->DOC,
            'DOU' => $this->DOU,
        ]);

        return $dataProvider;
    }
}
