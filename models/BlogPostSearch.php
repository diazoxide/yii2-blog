<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\yii2blog\models;

use diazoxide\yii2blog\traits\IActiveStatus;
use yii\data\ActiveDataProvider;

/**
 * BlogPostSearch represents the model behind the search form about `diazoxide\yii2blog\models\BlogPost`.
 */
class BlogPostSearch extends BlogPost
{
    const SCENARIO_ADMIN = 'admin';
    const SCENARIO_USER = 'user';

    public $q;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'click', 'user_id', 'status'], 'integer'],
            [['title', 'q'], 'string'],
        ];
    }

    public function formName(){
        if($this->scenario == self::SCENARIO_USER){
            return '';
        } else return parent::formName();
    }
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN] = ['id', 'category_id', 'click', 'user_id', 'status', 'title'];
        $scenarios[self::SCENARIO_USER] = ['category_id', 'q'];
        return $scenarios;
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
        $query = BlogPost::find();
        $query->orderBy(['created_at' => SORT_DESC]);

        if ($this->scenario == self::SCENARIO_USER) {
            $query->andWhere([BlogCategory::tableName() . '.status' => IActiveStatus::STATUS_ACTIVE])->innerJoinWith('category')
                ->andWhere([BlogCategory::tableName() . '.status' => IActiveStatus::STATUS_ACTIVE]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->module->blogPostPageCount,
            ]
        ]);

        if (!($this->load($params, ($this->scenario == self::SCENARIO_USER) ? '' : 'BlogPostSearch') && $this->validate())) {
            return $dataProvider;
        }


        if ($this->scenario == self::SCENARIO_USER) {
            if ($this->category_id) {
                $query->andFilterWhere([$this::tableName() . '.category_id' => $this->category_id]);
            }
            $query
                ->andFilterWhere(['like', $this::tableName() . '.title', $this->q])
                ->orFilterWhere(['like', $this::tableName() . '.content', $this->q]);
        }


        if ($this->scenario == self::SCENARIO_ADMIN) {
            $query->andFilterWhere([
                $this::tableName() . '.category_id' => $this->category_id,

                $this::tableName() . '.id' => $this->id,
                $this::tableName() . '.status' => $this->status,
                $this::tableName() . '.click' => $this->click,
                $this::tableName() . '.user_id' => $this->user_id,
                $this::tableName() . '.created_at' => $this->created_at,
                $this::tableName() . '.updated_at' => $this->updated_at,
            ]);
            $query
                ->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.title', $this->title])
                ->andFilterWhere(['like', $this::tableName() . '.content', $this->content]);
        }

        return $dataProvider;
    }
}
