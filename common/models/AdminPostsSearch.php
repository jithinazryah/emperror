<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AdminPosts;

/**
 * AdminPostsSearch represents the model behind the search form about `common\models\AdminPosts`.
 */
class AdminPostsSearch extends AdminPosts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'admin', 'masters', 'appointments', 'estimated_proforma', 'port_call_data', 'close_estimate', 'status', 'CB', 'UB'], 'integer'],
            [['post_name', 'DOC', 'DOU'], 'safe'],
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
        $query = AdminPosts::find();

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
            'admin' => $this->admin,
            'masters' => $this->masters,
            'appointments' => $this->appointments,
            'estimated_proforma' => $this->estimated_proforma,
            'port_call_data' => $this->port_call_data,
            'close_estimate' => $this->close_estimate,
            'status' => $this->status,
            'CB' => $this->CB,
            'UB' => $this->UB,
            'DOC' => $this->DOC,
            'DOU' => $this->DOU,
        ]);

        $query->andFilterWhere(['like', 'post_name', $this->post_name]);

        return $dataProvider;
    }
}
