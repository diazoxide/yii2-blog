<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 09.04
 * Time: 18:13
 */

namespace diazoxide\blog\widgets;


use diazoxide\blog\models\BlogPost;
use diazoxide\blog\Module;
use diazoxide\blog\widgets\social\AddThis;
use diazoxide\blog\widgets\social\FacebookComments;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;

class SocialMedia extends Widget
{

    public $body_options = ['tag' => 'div', 'class' => 'row'];
    public $show_title = true;
    public $title_options = ['class' => 'widget_title top-buffer-20-xs'];
    public $title = "Social Media";

    public $addThisOptions = [];

    /**
     */
    public function init()
    {
        $this->addThisOptions['pub_id'] = Yii::$app->getModule('blog')->social['addthis']['pub_id'] ?? null;
    }

    /**
     * @return string|void
     * @throws \Exception
     */
    public function run()
    {

        $bodyTag = ArrayHelper::remove($this->body_options, 'tag', 'div') ?? 'div';
        echo Html::beginTag($bodyTag, $this->body_options);


        if ($this->show_title) {
            $titleTag = ArrayHelper::remove($this->title_options, 'tag', 'div') ?? 'div';
            echo Html::tag($titleTag, $this->title, $this->title_options);
        }

        if ($this->addThisOptions['pub_id'] !== null) {
            echo AddThis::widget(

                $this->addThisOptions

            );
        }

        echo Html::endTag($bodyTag);

    }

}