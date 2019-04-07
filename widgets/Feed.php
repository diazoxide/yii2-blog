<?php

namespace diazoxide\blog\widgets;

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogPost;
use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use diazoxide\blog\traits\FeedTrait;
use kartik\select2\Select2;
use kop\y2sp\ScrollPager;
use nirvana\infinitescroll\InfiniteScrollPager;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

class Feed extends \yii\bootstrap\Widget
{
    const TYPE_RANDOM = 1;
    const TYPE_POPULAR = 2;
    const TYPE_RECENT = 3;

    public $category_id;
    public $show_category_filter = false;

    public $custom_css;
    public $custom_js;

    public $title = "Posts";

    public $type = Feed::TYPE_RECENT;

    /*
     * Counts and offsets
     * This parameters works only
     * when pager parameter is false
     * */
    public $items_count = 10;
    public $offset = 0;

    public $item_image_type = 'mthumb';
    public $item_read_more_button_text = 'Read more...';
    public $item_read_more_button_icon_class = 'fa fa-eye';
    public $item_date_type = 'relativeTime';
    public $item_brief_length = 100;
    public $item_brief_suffix = '...';
    public $item_title_length = 100;
    public $item_title_suffix = '...';

    public $item_image_container_options = ['class' => 'col-xs-3'];
    public $item_content_container_options = ['class' => 'col-xs-9'];
    public $item_info_container_options = [];
    public $item_options = ['tag' => 'article', 'class' => 'item row top-buffer-20-xs'];
    public $item_body_options = ['tag' => 'div', 'class' => 'body'];
    public $item_title_options = ['tag' => 'h5', 'class' => 'nospaces-xs'];
    public $item_brief_options = ['tag' => 'p', 'class' => 'nospaces-xs'];
    public $item_read_more_button_options = ['class' => 'btn btn-warning'];
    public $list_options = ['tag' => 'div', 'class' => 'feed-widget-listview'];
    public $title_options = ['tag' => 'div', 'class' => 'row'];
    public $header_options = ['tag' => 'div', 'class' => 'header'];
    public $body_options = ['tag' => 'div', 'class' => 'body'];
    public $active_title_options = ['class' => 'text-warning'];


    public $infinite_scroll = false;
    public $infinite_scroll_element_scroll = true;
    public $load_more_button = false;
    public $active_title = false;
    public $active_title_url = null;

    public $show_title = false;
    public $show_category_title = false;
    public $show_pager = false;
    public $show_item_brief = false;
    public $show_item_title = true;
    public $show_item_info = true;
    public $show_item_read_more_button = false;
    public $show_item_category = false;
    public $show_item_category_icon = false;
    public $show_item_category_with_icon = false;
    public $show_item_views = false;
    public $show_item_date = true;

    public $days_interval = 0;

    protected $_listViewId;
    protected $_pager = null;
    protected $_category = null;
    protected $_category_filter_parameter;

    protected $_infiniteScrollPagerStatusOptions = ['tag' => 'div', 'class' => 'diazoxide_infinite_scroll_pager_status'];

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        /* Init. category model */

        $this->_listViewId = $this->id . "_list_view";

        /*
         * Show Category Filter in widget
         * User can instantly change category from widget
         * */
        if ($this->show_category_filter) {
            $this->_category_filter_parameter = $this->id . '_category';

            $categoryId = Yii::$app->request->get($this->_category_filter_parameter);
            if ($categoryId) {
                $this->category_id = $categoryId;
            }
        }

        $this->_category = $this->getCategory();


        if ($this->_category) {
            if ($this->show_category_title) {
                $this->title = $this->_category->icon . ' ' . $this->_category->title;
            }
            if ($this->show_item_read_more_button) {
                if ($this->_category->read_more_text) {
                    $this->item_read_more_button_text = $this->_category->read_more_text;
                }
                if ($this->_category->read_icon_class) {
                    $this->item_read_more_button_icon_class = $this->_category->read_icon_class;
                }
            }
        }

        if ($this->infinite_scroll || $this->load_more_button) {
            $this->show_pager = true;
            $this->_pager = [
                'class' => \diazoxide\infinitescroll\InfiniteScrollPager::class,
                'contentSelector' => '#' . $this->_listViewId,
                'pluginOptions' => [
                    'append' => '#' . $this->_listViewId . ' .' . $this->_listViewId . '_item',
                    'elementScroll' => $this->infinite_scroll_element_scroll,
                    'status' => "#{$this->id} .{$this->_infiniteScrollPagerStatusOptions['class']}"
                ]
            ];
            $this->options['style'] = 'position:relative;';
        }

        Html::addCssClass($this->list_options, $this->_listViewId);
        Html::addCssClass($this->item_options, $this->_listViewId . '_item');

        $this->getView()->registerJs($this->custom_js);
        $this->getView()->registerCss($this->custom_css);
    }

    /**
     * @return string|void
     * @throws \Exception
     */
    public function run()
    {
        echo Html::beginTag('div', $this->options);

        $this->renderHeader();

        $this->renderBody();

        echo Html::endTag('div');
    }

    /**
     * @throws \Exception
     */
    public function renderBody()
    {

        $options = $this->body_options;

        $tag = ArrayHelper::remove($options, 'tag', 'div');

        echo Html::beginTag($tag, $options);

        echo ListView::widget([
            'id' => $this->_listViewId,
            'dataProvider' => $this->getDataProvider(),
            'options' => array_filter($this->list_options),
            'itemOptions' => array_filter($this->item_options),
            'layout' => "{items}" . ($this->show_pager ? "{pager}" : ""),
            'itemView' => '@vendor/diazoxide/yii2-blog/widgets/views/_feed_item',
            'viewParams' => [
                'bodyOptions' => $this->item_body_options,
                'showBrief' => $this->show_item_brief,
                'briefLength' => $this->item_brief_length,
                'briefSuffix' => $this->item_brief_suffix,
                'titleLength' => $this->item_title_length,
                'titleSuffix' => $this->item_title_suffix,
                'dateType' => $this->item_date_type,
                'imageType' => $this->item_image_type,
                'showCategory' => $this->show_item_category,
                'showCategoryIcon' => $this->show_item_category_icon,
                'showCategoryWithIcon' => $this->show_item_category_with_icon,
                'showDate' => $this->show_item_date,
                'showViews' => $this->show_item_views,
                'imageContainerOptions' => $this->item_image_container_options,
                'contentContainerOptions' => $this->item_content_container_options,
                'showTitle' => $this->show_item_title,
                'titleOptions' => $this->item_title_options,
                'briefOptions' => $this->item_brief_options,
                'showReadMoreButton' => $this->show_item_read_more_button,
                'readMoreButtonOptions' => $this->item_read_more_button_options,
                'readMoreButtonText' => $this->item_read_more_button_text,
                'readMoreButtonIconClass' => $this->item_read_more_button_icon_class,
                'infoContainerOptions' => $this->item_info_container_options
            ],

            'pager' => $this->_pager
        ]);

        $this->renderInfinityScrollStatusHtml();
        echo Html::endTag($tag);

    }

    /**
     * Building Data provider
     * @return ActiveDataProvider
     */
    private function getDataProvider()
    {
        $query = BlogPost::find();

        $query->where(['status' => IActiveStatus::STATUS_ACTIVE])
            ->orderBy($this->getOrderFromType());

        $query->andWhere('FROM_UNIXTIME(published_at) <= NOW()');


        if ($this->days_interval) {
            $query->andWhere('FROM_UNIXTIME(published_at) > NOW() - INTERVAL ' . $this->days_interval . ' DAY');
        }

        if ($this->_category) {
            $catIds = ArrayHelper::map(BlogCategory::findOne($this->category_id)->getDescendants()->all(), 'id', 'id');
            $catIds[] = $this->category_id;
            $query->andFilterWhere(['in', 'category_id', $catIds]);
        }

        if (!$this->show_pager) {
            $query->offset($this->offset);
            $query->limit($this->items_count);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => $this->show_pager ? [
//                'pageSize' => $this->items_count,
                'pageParam' => $this->id . '_page',
                'pageSizeParam' => $this->id . '_page_size',
            ] : false
        ]);
    }

    /**
     * @return array|Expression
     */
    protected function getOrderFromType()
    {
        switch ($this->type) {
            case self::TYPE_RANDOM:
                return new Expression('rand()');
            case self::TYPE_RECENT:
                return ['published_at' => SORT_DESC];
            case self::TYPE_POPULAR:
                return ['click' => SORT_DESC];
            default:
                return [];
        }
    }


    /**
     * @return BlogCategory|null
     */
    public function getCategory()
    {
        return BlogCategory::findOne($this->category_id);
    }

    public function renderInfinityScrollStatusHtml()
    {
        $options = $this->_infiniteScrollPagerStatusOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'div');

        echo Html::beginTag($tag, $options);
        echo Html::tag('div', Module::t('', 'Loading...'), ['class' => 'infinite-scroll-request']);
        echo Html::tag('div', Module::t('', 'End of content.'), ['class' => 'infinite-scroll-last']);
        echo Html::tag('div', Module::t('', 'No more posts to load.'), ['class' => 'infinite-scroll-error']);
        echo Html::endTag($tag);
    }

    /**
     * @throws \Exception
     */
    public function renderCategoryFilter()
    {
        echo Html::beginForm('', 'get', ['class' => 'form']);
        echo Select2::widget([
            'data' => ArrayHelper::map(BlogCategory::find()->all(), 'id', 'title'),
            'name' => $this->_category_filter_parameter,
            'size' => Select2::SIZE_MEDIUM,
            'value' => $this->category_id,
            'options' => [
                'placeholder' => Module::t('', 'Select Category'),
                'multiple' => false,
                'onchange' => 'this.form.submit()'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'addon' => ['prepend' => [
                'content' => '<i class="fa fa-list"></i>'
            ]],
        ]);
        echo Html::endForm();
    }

    /**
     * @throws \Exception
     */
    public function renderHeader()
    {
        $options = $this->header_options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');

        echo Html::beginTag($tag, $options);

        if ($this->show_title) {
            $this->renderTitle();
        }

        if ($this->show_category_filter) {
            $this->renderCategoryFilter();
        }

        echo Html::endTag($tag);
    }

    /**
     * Rendering title
     * if show_title parameter is true printing title
     * and if active_title is true, showing title as link
     */
    public function renderTitle()
    {

        $title = $this->title;
        if ($this->active_title) {
            $url = $this->active_title_url;
            if (!$url && $this->_category) {
                $url = $this->_category->url;
            }
            $title = Html::a($title, $url, $this->active_title_options);
        }

        echo Html::tag(
            isset($this->title_options['tag']) && !empty($this->title_options['tag']) ? $this->title_options['tag'] : 'div',
            $title,
            $this->title_options
        );
    }

}
