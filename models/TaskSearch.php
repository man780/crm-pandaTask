<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Task;

/**
 * TaskSearch represents the model behind the search form of `app\models\Task`.
 */
class TaskSearch extends Task
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority', 'deadline', 'to', 'to_copywriter_type', 'to_copywriter_scope', 'to_copywriter_theme', 'to_developer_type', 'shown_by_executor', 'time', 'status'], 'integer'],
            [['title', 'body', 'to_copywriter_text', 'to_copywriter_special', 'to_translator_languages', 'to_developer_status'], 'safe'],
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
        $query = Task::find();

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
            'priority' => $this->priority,
            'deadline' => $this->deadline,
            'to' => $this->to,
            'to_copywriter_type' => $this->to_copywriter_type,
            'to_copywriter_scope' => $this->to_copywriter_scope,
            'to_copywriter_theme' => $this->to_copywriter_theme,
            'to_developer_type' => $this->to_developer_type,
            'shown_by_executor' => $this->shown_by_executor,
            'time' => $this->time,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'to_copywriter_text', $this->to_copywriter_text])
            ->andFilterWhere(['like', 'to_copywriter_special', $this->to_copywriter_special])
            ->andFilterWhere(['like', 'to_translator_languages', $this->to_translator_languages])
            ->andFilterWhere(['like', 'to_developer_status', $this->to_developer_status]);

        return $dataProvider;
    }
}
