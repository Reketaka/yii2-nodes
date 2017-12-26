<?php

namespace reketaka\nodes\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NodesSearch represents the model behind the search form about `reketaka\nodes\models\Nodes`.
 */
class NodesSearch extends Nodes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'default', 'value' => '0'],
            [['id', 'parent_id', 'primary_key'], 'integer'],
            [['alias', 'title'], 'safe'],
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
        $query = Nodes::find();

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
            'parent_id' => $this->parent_id,
            'primary_key' => $this->primary_key,
        ]);

        $query->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }

    /**
     * Возврашает текущего страницу детей которой мы просматриваем
     * если false значит находимся в корне
     * @return bool|null|static
     */
    public function getPage(){
        if($this->parent_id == 0){
            return false;
        }

        return Nodes::findOne($this->parent_id);
    }
}

