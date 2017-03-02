<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Survey;

/**
 * SurveySearch represents the model behind the search form about `app\models\Survey`.
 */
class SurveySearch extends Survey
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'global_score', 'guest_country_id'], 'integer'],
            [['checkout_date', 'apartment', 'source_title', 'guest_name', 'guest_email', 'guest_address', 'touroperator_name', 'best_employee_name', 'best_employee_group_name', 'good_things', 'bad_things', 'met_expectation_title', 'evolution_title', 'suggestions'], 'safe'],
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
        $query = Survey::find()->with('source.translations');

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
            'checkout_date' => $this->checkout_date,
            'global_score' => $this->global_score,
            'guest_country_id' => $this->guest_country_id,
        ]);

        $query->andFilterWhere(['like', 'apartment', $this->apartment])
            ->andFilterWhere(['like', 'source_title', $this->source_title])
            ->andFilterWhere(['like', 'guest_name', $this->guest_name])
            ->andFilterWhere(['like', 'guest_email', $this->guest_email])
            ->andFilterWhere(['like', 'guest_address', $this->guest_address])
            ->andFilterWhere(['like', 'touroperator_name', $this->touroperator_name])
            ->andFilterWhere(['like', 'best_employee_name', $this->best_employee_name])
            ->andFilterWhere(['like', 'best_employee_group_name', $this->best_employee_group_name])
            ->andFilterWhere(['like', 'good_things', $this->good_things])
            ->andFilterWhere(['like', 'bad_things', $this->bad_things])
            ->andFilterWhere(['like', 'met_expectation_title', $this->met_expectation_title])
            ->andFilterWhere(['like', 'evolution_title', $this->evolution_title])
            ->andFilterWhere(['like', 'suggestions', $this->suggestions]);

        return $dataProvider;
    }
}
