<?php

namespace diazoxide\blog\models;

use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * @property Module module
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

    public function behaviors()
    {
        return [];
    }

    public function formName()
    {
        if ($this->scenario == self::SCENARIO_USER) {
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

        if ($this->scenario == self::SCENARIO_ADMIN) {
            $query->orderBy(['id' => SORT_DESC]);

        }
        if ($this->scenario == self::SCENARIO_USER) {
            $query->orderBy(['published_at' => SORT_DESC]);

            $query->andWhere([BlogPost::tableName() . '.status' => IActiveStatus::STATUS_ACTIVE])
                ->innerJoinWith('category')
                ->andWhere([BlogCategory::tableName() . '.status' => IActiveStatus::STATUS_ACTIVE]);

            $query->andWhere('FROM_UNIXTIME(' . BlogPost::tableName() . '.published_at) <= NOW()');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->module->blogPostPageCount,
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->scenario == self::SCENARIO_USER) {
            $query
                ->andFilterWhere(['like', $this::tableName() . '.title', $this->q])
                ->orFilterWhere(['like', $this::tableName() . '.content', $this->q]);
        }


        if ($this->scenario == self::SCENARIO_ADMIN) {

            $query->andFilterWhere([
                $this::tableName() . '.id' => $this->id,
                $this::tableName() . '.status' => $this->status,
                $this::tableName() . '.click' => $this->click,
                $this::tableName() . '.user_id' => $this->user_id,
                $this::tableName() . '.created_at' => $this->created_at,
                $this::tableName() . '.updated_at' => $this->updated_at,
                $this::tableName() . '.published_at' => $this->published_at,
            ]);

            $query
                ->andFilterWhere(['like', $this::tableName() . '.title', $this->title])
                ->andFilterWhere(['like', $this::tableName() . '.tags', $this->tags])
                ->andFilterWhere(['like', $this::tableName() . '.content', $this->content])
                ->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug]);
        }
        if ($this->category_id) {
            $catIds = ArrayHelper::map(BlogCategory::findOne($this->category_id)->getDescendants()->all(), 'id', 'id');
            $catIds[] = $this->category_id;
            $query->andFilterWhere(['in', $this::tableName() . '.category_id', $catIds]);
        }

        return $dataProvider;
    }
}
