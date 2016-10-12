<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Vessel;

/**
 * VesselSearch represents the model behind the search form about `common\models\Vessel`.
 */
class VesselSearch extends Vessel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vessel_type', 'status', 'CB', 'UB'], 'integer'],
            [['vessel_name', 'imo_no', 'official', 'mmsi_no', 'owners_info', 'mobile', 'land_line', 'direct_line', 'fax', 'picture', 'dwt', 'grt', 'nrt', 'loa', 'beam', 'DOC', 'DOU'], 'safe'],
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
        $query = Vessel::find();

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
            'vessel_type' => $this->vessel_type,
            'status' => $this->status,
            'CB' => $this->CB,
            'UB' => $this->UB,
            'DOC' => $this->DOC,
            'DOU' => $this->DOU,
        ]);

        $query->andFilterWhere(['like', 'vessel_name', $this->vessel_name])
            ->andFilterWhere(['like', 'imo_no', $this->imo_no])
            ->andFilterWhere(['like', 'official', $this->official])
            ->andFilterWhere(['like', 'mmsi_no', $this->mmsi_no])
            ->andFilterWhere(['like', 'owners_info', $this->owners_info])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'land_line', $this->land_line])
            ->andFilterWhere(['like', 'direct_line', $this->direct_line])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'picture', $this->picture])
            ->andFilterWhere(['like', 'dwt', $this->dwt])
            ->andFilterWhere(['like', 'grt', $this->grt])
            ->andFilterWhere(['like', 'nrt', $this->nrt])
            ->andFilterWhere(['like', 'loa', $this->loa])
            ->andFilterWhere(['like', 'beam', $this->beam]);

        return $dataProvider;
    }
}
