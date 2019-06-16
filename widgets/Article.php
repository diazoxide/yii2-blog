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
use diazoxide\blog\widgets\social\FacebookComments;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;

class Article extends Widget {

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
	public $show_comments = false;

	public $image_type = 'mthumb';
	public $read_more_button_text = 'Read more...';
	public $read_more_button_icon_class = 'fa fa-eye';
	public $date_type = 'relativeTime';
	public $brief_length = 100;
	public $brief_suffix = '...';
	public $title_length = 100;
	public $title_suffix = '...';
	public $active_title = true;

	public $image_container_options = [ 'class' => 'col-xs-3' ];
	public $content_container_options = [ 'class' => 'col-xs-9' ];
	public $info_container_options = [];
	public $options = [ 'tag' => 'article', 'class' => 'item row top-buffer-20-xs' ];
	public $body_options = [ 'tag' => 'div', 'class' => 'body' ];
	public $title_options = [ 'tag' => 'h5', 'class' => 'nospaces-xs' ];
	public $content_options = [ 'tag' => 'div', 'class' => 'nospaces-xs' ];
	public $brief_options = [ 'tag' => 'p', 'class' => 'nospaces-xs' ];
	public $read_more_button_options = [ 'class' => 'btn btn-warning' ];

	/**
	 * @throws NotFoundHttpException
	 */
	public function init() {

		if ( $this->post == null && $this->post_id > 0 ) {
			$this->post = BlogPost::findOne( $this->post_id );
		}

		if ( ! $this->post ) {
			throw new NotFoundHttpException( 'The requested post does not exist.' );
		}

	}

	/**
	 * @return string|void
	 */
	public function run() {

		$bodyTag = ArrayHelper::remove( $this->body_options, 'tag', 'div' ) ?? 'div';
		echo Html::beginTag( $bodyTag, $this->body_options );

		/**
		 * Building image container Html
		 */
		$imageContainerTag = ArrayHelper::remove( $this->image_container_options, 'tag', 'div' ) ?? 'div';
		echo Html::tag(
			$imageContainerTag,
			Html::a(
				Html::img(
					$this->image_type == 'original' ? $this->post->getImageFileUrl( 'banner' ) : $this->post->getThumbFileUrl( 'banner',
						$this->image_type ),
					[ 'class' => 'img-responsive pull-left' ]
				),
				$this->post->url
			),
			$this->image_container_options
		);

		echo Html::beginTag( 'div', $this->content_container_options );

		/**
		 * Building title Html
		 */
		if ( $this->show_title ) {

			$titleTag = ArrayHelper::remove( $this->title_options, 'tag', 'div' ) ?? 'div';

			$title = Html::tag(
				$titleTag,
				StringHelper::truncate( Html::encode( $this->post->title ), $this->title_length,
					$this->title_suffix ),
				$this->title_options
			);

			/*
			 * If active title is true than show title as link
			 * */
			echo $this->active_title ? Html::a( $title, $this->post->url ) : $title;
		}

		/** @var array $infoContainerOptions */
		echo Html::beginTag( 'div', $this->info_container_options );


		/*
		 * Category
		 * */
		if ( $this->show_category ) {
			echo Html::tag( 'span', $this->post->category->title, [ 'class' => "label label-warning" ] );
		} elseif ( $this->show_category_with_icon ) {
			echo Html::tag( 'span', $this->post->category->titleWithIcon, [ 'class' => 'label label-warning' ] );
		} elseif ( $this->show_category_icon ) {
			echo Html::tag( 'span', $this->post->category->icon, [ 'class' => 'label label-warning' ] );
		}

		/*
		 * Datetime
		 * */
		if ( $this->show_date ) {
			echo Html::tag( 'span',
				implode( '',
					[
						Html::tag( 'i', '', [ 'class' => 'fa fa-calendar' ] ),
						' ',
						Yii::$app->formatter->format( $this->post->published_at, $this->date_type )
					]
				)
			);
		}

		/*
		 * Views
		 * */
		if ( $this->show_views ) {
			echo Html::tag( 'span',
				implode( '',
					[
						Html::tag( 'i', '', [ 'class' => 'fa fa-eye' ] ),
						' ',
						$this->post->click
					]
				)
			);
		}

		echo Html::endTag( 'div' );

		/*
		 * Brief
		 * */
		if ( $this->show_brief ) {

			$briefTag = ArrayHelper::remove( $this->brief_options, 'tag', 'div' ) ?? 'div';
			echo Html::tag(
				$briefTag,
				StringHelper::truncate( Html::encode( $this->post->brief ), $this->brief_length,
					$this->brief_suffix ),
				$this->brief_options
			);
		}


		/*
		 * Content
		 * */
		if ( $this->show_content ) {
			$contentTag = ArrayHelper::remove( $this->content_options, 'tag', 'div' ) ?? 'div';
			echo Html::tag(
				$contentTag,
				$this->post->content,
				$this->content_options
			);
		}


		/**
		 * Read more button
		 */
		if ( $this->show_read_more_button ) {

			echo Html::a(
				'<i class="' . $this->read_more_button_icon_class . '"></i> ' . $this->read_more_button_text,
				$this->post->url,
				$this->read_more_button_options
			);
		}

		echo Html::endTag( 'div' );

		if($this->show_comments){
		    $this->renderComments();
        }

		echo Html::endTag( $bodyTag );

	}

	public function renderComments() {
		if ( $this->post->module->enableComments && $this->post->show_comments && $this->post->type->has_comment ) :?>
            <section class="top-buffer-20-xs">

                <div class="row">
                    <div class="widget_title"><?= Module::t( '', 'Comments' ); ?></div>

					<?php if ( $this->post->module->enableFacebookComments ): ?>

                        <div class="col-sm-12">
							<?php
							if ( isset( $this->context->module->social['facebook']['app_id'] ) ) {
								echo FacebookComments::widget(
									[
										'app_id' => $this->context->module->social['facebook']['app_id'],
										'data'   => [ 'width' => '100%', 'numposts' => '5' ]
									]
								);
							}
							?>
                        </div>


					<?php endif; ?>

                </div>

            </section>
		<?php endif;
	}
}