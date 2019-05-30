<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 09.04
 * Time: 18:13
 */

namespace diazoxide\blog\widgets;


use diazoxide\blog\models\BlogPost;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;

class Article extends Widget
{

    public $post_id;

    public $post;

    public $show_content = false;
    public $show_brief = false;
    public $show_title = true;
    public $show_info = true;
    public $show_read_more_button = false;
    public $show_category = false;
    public $show_category_icon = false;
    public $show_category_with_icon = false;
    public $show_views = false;
    public $show_date = true;

    public $image_type = 'mthumb';
    public $read_more_button_text = 'Read more...';
    public $read_more_button_icon_class = 'fa fa-eye';
    public $date_type = 'relativeTime';
    public $brief_length = 100;
    public $brief_suffix = '...';
    public $title_length = 100;
    public $title_suffix = '...';

    public $image_container_options = ['class' => 'col-xs-3'];
    public $content_container_options = ['class' => 'col-xs-9'];
    public $info_container_options = [];
    public $options = ['tag' => 'article', 'class' => 'item row top-buffer-20-xs'];
    public $body_options = ['tag' => 'div', 'class' => 'body'];
    public $title_options = ['tag' => 'h5', 'class' => 'nospaces-xs'];
    public $content_options = ['tag' => 'div', 'class' => 'nospaces-xs'];
    public $brief_options = ['tag' => 'p', 'class' => 'nospaces-xs'];
    public $read_more_button_options = ['class' => 'btn btn-warning'];

    /**
     * @throws NotFoundHttpException
     */
    public function init()
    {

        if ($this->post == null && $this->post_id > 0) {
            $this->post = BlogPost::findOne($this->post_id);
        }

        if ( ! $this->post) {
            throw new NotFoundHttpException('The requested post does not exist.');
        }

    }

    /**
     * @return string|void
     */
    public function run()
    {

        $bodyTag = ArrayHelper::remove($this->body_options, 'tag', 'div');
        echo Html::beginTag($bodyTag, $this->body_options);

        /**
         * Building image container Html
         */
        echo Html::tag(
            isset($this->image_container_options['tag']) && ! empty($this->image_container_options['tag']) ? $this->image_container_options['tag'] : 'div',
            Html::a(
                Html::img(
                    $this->post->getThumbFileUrl('banner', $this->image_type),
                    ['class' => 'img-responsive pull-left']
                ),
                $this->post->url
            ),
            $this->image_container_options
        );

        echo Html::beginTag('div', $this->content_container_options);

        /**
         * Building title Html
         */
        if ($this->show_title) {
            echo Html::a(
                Html::tag(
                    isset($this->title_options['tag']) && ! empty($this->title_options['tag']) ? $this->title_options['tag'] : 'div',
                    StringHelper::truncate(Html::encode($this->post->title), $this->title_length,
                        $this->title_suffix),
                    $this->title_options
                ),
                $this->post->url
            );
        }

        /** @var array $infoContainerOptions */
        echo Html::beginTag(
            'div',
            $this->info_container_options);


        /*
         * Category
         * */
        if ($this->show_category) {
            echo Html::tag('span', $this->post->category->title, ['class' => "label label-warning"]);
        } elseif ($this->show_category_with_icon) {
            echo Html::tag('span', $this->post->category->titleWithIcon, ['class' => 'label label-warning']);
        } elseif ($this->show_category_icon) {
            echo Html::tag('span', $this->post->category->icon, ['class' => 'label label-warning']);
        }

        /*
         * Datetime
         * */
        if ($this->show_date) {
            echo Html::tag('span',
                implode('',
                    [
                        Html::tag('i', '', ['class' => 'fa fa-calendar']),
                        ' ',
                        Yii::$app->formatter->format($this->post->published_at, $this->date_type)
                    ]
                )
            );
        }

        /*
         * Views
         * */
        if ($this->show_views) {
            echo Html::tag('span',
                implode('',
                    [
                        Html::tag('i', '', ['class' => 'fa fa-eye']),
                        ' ',
                        $this->post->click
                    ]
                )
            );
        }

        echo Html::endTag('div');

        /*
         * Brief
         * */
        if ($this->show_brief) {
            echo Html::tag(
                isset($this->brief_options['tag']) && ! empty($this->brief_options['tag']) ? $this->brief_options['tag'] : 'div',
                StringHelper::truncate(Html::encode($this->post->brief), $this->brief_length,
                    $this->brief_suffix),
                $this->brief_options
            );
        }


        /*
         * Content
         * */
        if ($this->show_content) {
            echo Html::tag(
                isset($this->content_options['tag']) && ! empty($this->content_options['tag']) ? $this->content_options['tag'] : 'div',
                $this->post->content,
                $this->content_options
            );
        }


        /**
         * Read more button
         */
        if ($this->show_read_more_button) {

            echo Html::a(
                '<i class="' . $this->read_more_button_icon_class . '"></i> ' . $this->read_more_button_text,
                $this->post->url,
                $this->read_more_button_options
            );
        }
        echo Html::endTag('div');

        echo Html::endTag($bodyTag);

    }

}