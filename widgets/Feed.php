<?php

namespace diazoxide\blog\widgets;

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogPost;
use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use diazoxide\blog\traits\FeedTrait;
use kop\y2sp\ScrollPager;
use nirvana\infinitescroll\InfiniteScrollPager;
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

    public $custom_css;
    public $custom_js;

    public $title = "Posts";

    public $type = Feed::TYPE_RECENT;


    public $items_count = 10;
    public $offset = 0;
    public $item_image_type = 'mthumb';
    public $item_read_more_button_text = 'Read more...';
    public $item_read_more_button_icon_class = 'fa fa-eye';

    public $item_image_container_options = ['class' => 'col-xs-3'];
    public $item_content_container_options = ['class' => 'col-xs-9'];

    public $item_info_container_options = [];
    public $item_options = ['tag' => 'article', 'class' => 'item row top-buffer-20-xs'];
    public $item_title_options = ['tag' => 'h5', 'class' => 'nospaces-xs'];
    public $item_brief_options = ['tag' => 'p', 'class' => 'nospaces-xs'];
    public $item_read_more_button_options = ['class' => 'btn btn-warning'];
    public $list_options = ['tag' => 'div', 'class' => 'feed-widget-listview'];
    public $title_options = ['tag' => 'div', 'class' => 'row'];

    public $item_brief_length = 100;
    public $item_brief_suffix = '...';
    public $item_title_length = 100;
    public $item_title_suffix = '...';

    public $infinite_scroll = false;
    public $load_more_button = false;

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

    protected $_infiniteScrollPagerStatusOptions = ['tag' => 'div', 'class' => 'diazoxide_infinite_scroll_pager_status'];

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        /* Init. category model */
        $this->_category = $this->getCategory();

        $this->_listViewId = $this->id . "_list_view";

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
                'class' => \diazoxide\infinitescroll\InfiniteScrollPager::className(),
                'contentSelector' => '#' . $this->_listViewId,
                'pluginOptions' => [
                    'append' => '#' . $this->_listViewId . ' .' . $this->_listViewId . '_item',
                    'elementScroll' => true,
                    'status' => "#{$this->id} .{$this->_infiniteScrollPagerStatusOptions['class']}"
                ]
            ];
            $this->options['style']='position:relative;';
        }



        Html::addCssClass($this->list_options, $this->_listViewId);
        Html::addCssClass($this->item_options, $this->_listViewId . '_item');





        $this->getView()->registerJs($this->custom_js);
        $this->getView()->registerCss($this->custom_css);
    }

    public function run(){
        echo Html::beginTag('div', $this->options);

        if ($this->show_title) {
            echo Html::tag(
                isset($this->title_options['tag']) && !empty($this->title_options['tag']) ? $this->title_options['tag'] : 'div',
                $this->title,
                $this->title_options
            );

        }
        echo ListView::widget([
            'id' => $this->_listViewId,
            'dataProvider' => $this->getDataProvider(),
            'options' => array_filter($this->list_options),
            'itemOptions' => array_filter($this->item_options),
            'layout' => "{items}" . ($this->show_pager ? "{pager}" : ""),
            'itemView' => '@vendor/diazoxide/yii2-blog/widgets/views/_feed_item',
            'viewParams' => [
                'showBrief' => $this->show_item_brief,
                'briefLength' => $this->item_brief_length,
                'briefSuffix' => $this->item_brief_suffix,
                'titleLength' => $this->item_title_length,
                'titleSuffix' => $this->item_title_suffix,
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


        echo Html::endTag('div');
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

        if ($this->days_interval) {
            $query->andWhere('FROM_UNIXTIME(created_at) > NOW() - INTERVAL ' . $this->days_interval . ' DAY');
        }

        if ($this->_category) {
            $query->andWhere(['category_id' => $this->category_id]);
        }

        if (!$this->show_pager) {
            $query->offset($this->offset);
            $query->limit($this->items_count);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => $this->show_pager ? [
                'pageSize' => $this->items_count,
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
                return ['created_at' => SORT_DESC];
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
        echo Html::tag('div', Module::t('Loading...'), ['class' => 'infinite-scroll-request']);
        echo Html::tag('div', Module::t('End of content.'), ['class' => 'infinite-scroll-last']);
        echo Html::tag('div', Module::t('No more posts to load.'), ['class' => 'infinite-scroll-error']);
        echo Html::endTag($tag);
    }
}
