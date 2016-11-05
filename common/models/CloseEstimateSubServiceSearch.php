<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CloseEstimateSubService;

/**
 * CloseEstimateSubServiceSearch represents the model behind the search form about `common\models\CloseEstimateSubService`.
 */
class CloseEstimateSubServiceSearch extends CloseEstimateSubService
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'appointment_id', 'close_estimate_id', 'service_id', 'sub_service', 'status', 'CB', 'UB'], 'integer'],
            [['unit', 'unit_price', 'total'], 'number'],
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
        $query = CloseEstimateSubService::find();

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
            'sub_service' => $this->sub_service,
            'unit' => $this->unit,
            'unit_price' => $this->unit_price,
            'total' => $this->total,
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
