<?php

namespace diazoxide\blog\widgets;

use diazoxide\blog\models\BlogPost;
use diazoxide\blog\models\BlogPostSearch;
use diazoxide\blog\traits\IActiveStatus;
use kop\y2sp\ScrollPager;
use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

class Slider extends \yii\bootstrap\Widget
{
    public $itemsCount = 10;

    public function init()
    {
        parent::init();

        echo \diazoxide\revslider\widgets\Slider::widget([

            'items' => $this->getItems(),
            'config' => [
                "sliderType" => "standard",
                "sliderLayout" => "auto",
                "dottedOverlay" => "none",
                "delay" => 9000,
                "navigation" => [
                    "keyboardNavigation" => "off",
                    "keyboard_direction" => "horizontal",
                    "mouseScrollNavigation" => "off",
                    "mouseScrollReverse" => "default",
                    "onHoverStop" => "on",
                    "touch" => [
                        "touchenabled" => "on",
                        "touchOnDesktop" => "off",
                        "swipe_threshold" => 75,
                        "swipe_min_touches" => 50,
                        "swipe_direction" => "horizontal",
                        "drag_block_vertical" => false
                    ]
                    ,
                    "tabs" => [
                        "style" => "gyges-2",
                        "enable" => true,
                        "width" => 300,
                        "height" => 70,
                        "min_width" => 100,
                        "wrapper_padding" => 0,
                        "wrapper_color" => "rgba(10,10,10,0.7)",
                        "tmp" => '<div class="tp-tab-content">  <span class="tp-tab-date">{{param1}}</span>  <span class="tp-tab-title">{{title}}</span>  </div><div class="tp-tab-image"></div>',
                        "visibleAmount" => 5,
                        "hide_onmobile" => true,
                        "hide_under" => 500,
                        "hide_onleave" => false,
                        "hide_delay" => 200,
                        "direction" => "vertical",
                        "span" => false,
                        "position" => "inner",
                        "space" => 5,
                        "h_align" => "right",
                        "v_align" => "center",
                        "h_offset" => 0,
                        "v_offset" => 0
                    ]
                ],
                "responsiveLevels" => [1240, 1024, 778, 480],
                "visibilityLevels" => [1240, 1024, 778, 480],
                "gridwidth" => [1240, 1024, 778, 480],
                "gridheight" => [650, 768, 600, 320],
                "lazyType" => "none",
                "parallax" => [
                    "type" => "mouse",
                    "origo" => "slidercenter",
                    "speed" => 2000,
                    "speedbg" => 0,
                    "speedls" => 0,
                    "levels" => [2, 3, 4, 5, 6, 7, 12, 16, 10, 50, 47, 48, 49, 50, 51, 55],
                ],
                "shadow" => 0,
                "spinner" => "off",
                "stopLoop" => "off",
                "stopAfterLoops" => -1,
                "stopAtSlide" => -1,
                "shuffle" => "off",
                "autoHeight" => "off",
                "hideThumbsOnMobile" => "off",
                "hideSliderAtLimit" => 0,
                "hideCaptionAtLimit" => 0,
                "hideAllCaptionAtLilmit" => 0,
                "debugMode" => false,
                "fallbacks" => [
                    "simplifyAll" => "off",
                    "nextSlideOnWindowFocus" => "off",
                    "disableFocusListener" => false,
                ]
            ]

        ]);


    }

    public function getItems(){
        $posts = \diazoxide\blog\models\BlogPost::find()
            ->where(['status' => IActiveStatus::STATUS_ACTIVE,'is_slide'=>true])
            ->andWhere('FROM_UNIXTIME(published_at) <= NOW()')
            ->limit($this->itemsCount)->orderBy(['id' => SORT_DESC])->all();
        $items = [];
        foreach ($posts as $post) {
            $items[] = [
                'mainImage' => ['src' => $post->getImageFileUrl('banner')],
                'data' => [
                    'transition' => 'fade',
                    'slotamount' => 'default',
                    'hideafterloop' => 0,
                    'hideslideonmobile' => 'off',
                    'easein' => 'default',
                    'easeout' => 'default',
                    'masterspeed' => 300,
                    'thumb' => $post->getThumbFileUrl('banner', 'thumb'),
                    'rotate' => 0,
                    'saveperformance' => 'off',
                    'title' => $post->title,
                    'param1' => $post->category->getTitleWithIcon()
                ],
                'layers' => [
                    [
                        /**/
                        'content' => "Կարդալ ավելին",
                        'class'=>"tp-caption rev-btn",
                        "style" => "z-index: 7; min-width: 201px; max-width: 201px; max-width: 35px; max-width: 35px; white-space: normal; font-size: 14px; line-height: 14px; font-weight: 500; color: #262626; letter-spacing: px;font-family:Tahoma, Geneva, sans-serif;background-color:rgba(255,255,255,0.75);border-color:rgba(0,0,0,1);border-radius:3px 3px 3px 3px;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;",
                        'data' => [
                            /**/
                            'x' => "['left','left','center','center']",
                            'hoffset' => "['340','340','0','0']",
                            'y' => "['top','top','bottom','middle']",
                            'voffset' => "['567','567','45','116']",
                            'width' => "200",
                            'height' => "35",
                            'whitespace' => 'normal',
                            'type' => 'button',
                            'actions' => json_encode([
                                ['event' => 'click',
                                    'action' => 'simplelink',
                                    'target' => '_selft',
                                    'url' => $post->url,
                                    'delay' => 0,
                                ]
                            ]),
                            'responsive_offset' => 'on',
                            'responsive' => 'off',
                            'frames' => '[{"delay":1290,"speed":1500,"frame":"0","from":"y:bottom;rX:-20deg;rY:-20deg;rZ:0deg;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"+5880","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(0,0,0,1);bg:rgba(255,255,255,1);bs:solid;bw:0 0 0 0;"}]',
                            'textAlign' => "['center','center','center','center']",
                            "paddingtop" => "[10,10,10,10]",
                            "paddingright" => "[30,30,30,30]",
                            "paddingbottom" => "[10,10,10,10]",
                            "paddingleft" => "[30,30,30,30]",
                        ]
                    ],
                    [
                        'content' => $post->title,
                        'style'=>"z-index: 6; min-width: 511px; max-width: 511px; white-space: normal; font-size: 40px; line-height: 50px; font-weight: 400; color: #ffffff; letter-spacing: 0px;font-family:Tahoma, Geneva, sans-serif;",
                        'class'=>"tp-caption  tp-resizeme",
                        'data' => [
                            'x' => "['left','left','center','center']",
                            'hoffset' => "['100','100','0','0']",
                            'y' => "['top','top','top','top']",
                            'voffset' =>  "['103','103','103','51']",
                            "fontsize"=>"['20','20','30','20']",
                            "lineheight"=>"['30','30','35','25']",
                            'width' => "['300','300','300','322']",
                            'height' => "['none','none','none','51']",
                            'whitespace' => 'normal',
                            'type' => 'text',
                            'responsive_offset' => 'on',
                            'responsive' => 'off',
                            'frames' => '[{"delay":740,"speed":1220,"frame":"0","from":"y:bottom;rX:-20deg;rY:-20deg;rZ:0deg;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"+6390","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]',
                            'textAlign' => "['inherit','inherit','center','center']",
                            "paddingtop" => "[0,0,0,0]",
                            "paddingright" => "[0,0,0,0]",
                            "paddingbottom" => "[0,0,0,0]",
                            "paddingleft" => "[0,0,0,0]"
                        ]
                    ],
                    [
                        'style'=>"z-index: 5;background-color:rgba(0,0,0,0.5);",
                        'class'=>"tp-caption tp-shape tp-shapewrapper  tp-resizeme",
                        'data' => [
                            'x' => "['center','center','center','center']",
                            'hoffset' => "['0','0','0','0']",
                            'y' => "['middle','middle','middle','middle']",
                            'voffset' =>  "['0','0','0','0']" ,
                            "fontsize"=>"['20','20','30','20']",
                            "lineheight"=>"['30','30','35','25']",
                            'width' => "99999",
                            'height' => "99999",
                            'whitespace' => 'normal',
                            'type' => 'shape',
                            'responsive_offset' => 'on',
                            'responsive' => 'off',
                            'frames' => '[{"delay":500,"speed":360,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"+7820","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]',
                            "paddingtop" => "[0,0,0,0]",
                            "paddingright" => "[0,0,0,0]",
                            "paddingbottom" => "[0,0,0,0]",
                            "paddingleft" => "[0,0,0,0]"
                        ]
                    ]
                ]
            ];
        }
        return $items;
    }

}
