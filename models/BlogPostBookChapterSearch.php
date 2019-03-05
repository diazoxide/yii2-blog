<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\models;

use diazoxide\blog\traits\IActiveStatus;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * BlogPostSearch represents the model behind the search form about `diazoxide\blog\models\BlogPost`.
 */
class BlogPostBookChapterSearch extends BlogPostBookChapter
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
            [['id', 'book_id'], 'integer'],
            [['q'], 'string'],
        ];
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
        $scenarios[self::SCENARIO_ADMIN] = ['id', 'parent_id', 'status', 'title'];
        $scenarios[self::SCENARIO_USER] = ['q', 'id', 'parent_id'];
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
        $query = BlogPostBookChapter::find();
//        $query->orderBy(['id' => SORT_DESC]);

        if ($this->scenario == self::SCENARIO_USER) {
            $query
                ->innerJoinWith('book')
                ->andWhere([BlogPostBook::tableName() . '.status' => IActiveStatus::STATUS_ACTIVE]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        if (!($this->load($params, ($this->scenario == self::SCENARIO_USER) ? '' : $this::className()) && $this->validate())) {
            return $dataProvider;
        }


        if ($this->scenario == self::SCENARIO_USER) {
            if ($this->book_id) {
                //$query->innerJoinWith('parent');
                //$query->addSelect('('.$this::tableName().'.title+'.$this::tableName().'.content) as total');
                $query->andFilterWhere([$this::tableName() . '.book_id' => $this->book_id]);
            }

            if ($this->q) {
                // $qArray = explode(' ', $this->q);

                $query->andFilterWhere([$this::tableName() . '.title' => $this->q]);

                $re = '/\w+/mu';
                preg_match_all($re, $this->q, $qArray, PREG_PATTERN_ORDER);
                $qArray = $qArray[0];

                if (count($qArray) > 1) {
                    array_unshift($qArray, $this->q);
                }
                Yii::$app->session->setFlash('success', print_r($qArray, true));

                $orderString = '';
                foreach ($qArray as $key => $q) {
                    $orderConditions = [
                        [$this::tableName() . ".title", '=', $q],
                        [$this::tableName() . ".title", 'LIKE', $q],
                        [$this::tableName() . ".title", 'LIKE', "$q%"],
                        [$this::tableName() . ".title", 'LIKE', "%$q"],
                        [$this::tableName() . ".brief", '=', $q],
                        [$this::tableName() . ".brief", 'LIKE', $q],
                        [$this::tableName() . ".brief", 'LIKE', "$q%"],
                        [$this::tableName() . ".brief", 'LIKE', "%$q"],
                        [$this::tableName() . ".keywords", '=', $q],
                        [$this::tableName() . ".keywords", 'LIKE', $q],
                        [$this::tableName() . ".keywords", 'LIKE', "$q%"],
                        [$this::tableName() . ".keywords", 'LIKE', "%$q"],
                        [$this::tableName() . ".content", '=', $q],
                        [$this::tableName() . ".content", 'LIKE', $q],
                        [$this::tableName() . ".content", 'LIKE', "$q%"],
                        [$this::tableName() . ".content", 'LIKE', "%$q"],
                        ["parent.content", '=', $q],
                        ["parent.content", 'LIKE', $q],
                        ["parent.content", 'LIKE', "$q%"],
                        ["parent.content", 'LIKE', "%$q"],
                    ];
                    foreach ($orderConditions as $condKey => $condition) {
                        $priority = ($key + 1) * ($condKey + 1);
                        $orderString .= " WHEN  {$condition[0]} {$condition[1]} '{$condition[2]}' THEN $priority \n";
                        if ($condKey + 1 == count($orderConditions) && $key + 1 == count($qArray)) {
                            $priority++;
                            $orderString .= "ELSE $priority \n";
                        }
                    }
                    $query->orFilterWhere(['like', $this::tableName() . '.title', '%' . $q . '%', false])
                        ->orFilterWhere(['like', $this::tableName() . '.content', '%' . $q . '%', false])
                        ->orFilterWhere(['like', $this::tableName() . '.brief', '%' . $q . '%', false])
                        ->orFilterWhere(['like', $this::tableName() . '.keywords', '%' . $q . '%', false])
                        ->joinWith('parent parent')
                        ->orFilterWhere(['like', 'parent.keywords', $q]);

                }
                $query
                    ->orderBy(
                        [
                            "(CASE
                            $orderString
                          END)" => SORT_ASC
                        ]
                    );
            }
        }


//        if ($this->scenario == self::SCENARIO_ADMIN) {
//            $query->andFilterWhere([
//                $this::tableName() . '.category_id' => $this->category_id,
//
//                $this::tableName() . '.id' => $this->id,
//                $this::tableName() . '.status' => $this->status,
//                $this::tableName() . '.click' => $this->click,
//                $this::tableName() . '.user_id' => $this->user_id,
//                $this::tableName() . '.created_at' => $this->created_at,
//                $this::tableName() . '.updated_at' => $this->updated_at,
//            ]);
//            $query
//                ->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
//                ->andFilterWhere(['like', $this::tableName() . '.title', $this->title])
//                ->andFilterWhere(['like', $this::tableName() . '.content', $this->content]);
//        }

        return $dataProvider;
    }
}
