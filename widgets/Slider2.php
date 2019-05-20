<?php

namespace diazoxide\blog\widgets;

use Codeception\Exception\ElementNotFound;
use diazoxide\blog\models\BlogPost;
use diazoxide\blog\models\BlogPostType;
use diazoxide\blog\traits\IActiveStatus;
use yii\base\ViewNotFoundException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\StringHelper;

class Slider2 extends Widget
{
    public $itemsCount = 10;
	public $post_type = 'article';

    public function init()
    {
        parent::init();
        $css = '
.carousel-caption{
    width: 70%;
    padding: 0 30px;
    text-align: left;
    top: 40px;
    left: 0;
    right: auto;
    color: #fff;
}
.carousel-caption a{
  color:#fff;
}
.carousel-indicators li{
  text-indent:0;
  display:block;
  border-radius:0;
  border:solid 1px #000;
  height:90px !important;
  width:100% !important;
  overflow:hidden;
  font-size:12px;
  background:#000000bf;
  color:#dcdcdc;
  margin:0;
  padding:5px;
  text-align:left;
  transition:0.3s all ease;
}
.carousel-indicators li.active{
  background:#ffffffc4;
  color:#000;
}
.carousel-indicators li img{
  height:100%;
  margin-right:10px;
  float:left;
}
.carousel-indicators {
  background:#000000b8;
  position:absolute;
  margin-left:0;
  margin-right:0;
  margin: 0;
  left:auto;
  top:0;
  width: 30%;
  right:0;
  height:100%;
  overflow-y:auto;
}
.carousel {
  position:relative !important;
}
.carousel-indicator-content {
    height: 60px;
    overflow: hidden;
}';

        $this->view->registerCss($css);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        if(empty($this->items))
            return null;
        return Carousel::widget([
            'items' => $this->items,
            'options' => ['class' => 'carousel slide', 'data-interval' => '5000'],
            'indicatorsOptions' => ['tag' => 'ul', 'class' => 'carousel-indicators hidden-sm hidden-xs hidden-md'],
            'controls' => [
                '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>',
                '<span class="glyphicon glyphicon-chevron-right visible-md visible-xs visible-sm" aria-hidden="true"></span>'
            ]
        ]);
    }

	/**
	 * @return array
	 */
	public function getItems()
    {
    	$post_type = BlogPostType::findOne(['name'=>$this->post_type]);

    	if($post_type == null){
		    throw new ElementNotFound('The requested post type does not exist.');
	    }

        $posts = BlogPost::find()
            ->where(['status' => IActiveStatus::STATUS_ACTIVE, 'is_slide' => true,'type_id'=>$post_type->id])
            ->andWhere('FROM_UNIXTIME(published_at) <= NOW()')
            ->limit($this->itemsCount)
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $items = [];

        foreach ($posts as $post) {
            $items[] = [
                'content' => Html::tag('div', null, ['style' => 'height:450px; width:100%; background-image:url(' . $post->getImageFileUrl('banner') . '); background-size:cover']),
                'caption' =>
                    implode('', [
                        Html::a(
                            Html::tag('h4',
                                StringHelper::truncate($post->title, 200, '...')
                            ), $post->url
                        ),
//                        Html::tag('p', StringHelper::truncate($post->brief, 50, '...'))
                    ]),
                'indicator' => [
                    'content' => implode('', [
                        Html::img($post->getThumbFileUrl('banner', 'xsthumb')),
                        Html::tag('div', StringHelper::truncate($post->title, 50, '...'), ['class' => 'carousel-indicator-content']),
                        Html::tag('p', $post->category->getTitleWithIcon(), ['class' => 'text-right text-warning'])
                    ]),
//                    'options' => ['style'=>'overflow:hidden'],
//                    'options' => ['style' => 'zoom:2; background:url(' . $post->getThumbFileUrl('banner', 'xsthumb') . '); background-size:cover'],
                ],
                'options' => [],
            ];
        }
        return $items;
    }

}
