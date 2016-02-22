<?php

namespace infoweb\partials\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use infoweb\partials\models\PagePartial;

/**
 * Search represents the model behind the search form about `infoweb\partials\models\PagePartial`.
 */
class Search extends PagePartial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'safe'],
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
        $query = PagePartial::find();
        
        $query->andFilterWhere(['language' => Yii::$app->language]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ]
        ]);
        
        // Join the entity model as a relation
        $query->joinWith(['translations']);
        
        // enable sorting for the related column
        $dataProvider->sort->attributes['name'] = [
            'asc' => ['name' => SORT_ASC],
            'desc' => ['name' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
