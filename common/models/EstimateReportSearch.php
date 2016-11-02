<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EstimateReport;

/**
 * EstimateReportSearch represents the model behind the search form about `common\models\EstimateReport`.
 */
class EstimateReportSearch extends EstimateReport
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'appointment_id', 'status'], 'integer'],
            [['date_time', 'report'], 'safe'],
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
        $query = EstimateReport::find();

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
            'date_time' => $this->date_time,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'report', $this->report]);

        return $dataProvider;
    }
}
