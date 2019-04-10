<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 09.04
 * Time: 18:13
 */

namespace diazoxide\blog\widgets;


use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Carousel extends \yii\bootstrap\Carousel
{

    public $indicatorsOptions = ['tag' => 'ol', 'class' => 'carousel-indicators'];

    /**
     * Renders carousel indicators.
     * @return string the rendering result
     */
    public function renderIndicators()
    {
        if ($this->showIndicators === false) {
            return '';
        }
        $indicators = [];
        for ($i = 0, $count = count($this->items); $i < $count; $i++) {

            $options = isset($this->items[$i]['indicator']['options']) ? $this->items[$i]['indicator']['options'] : [];
            $options['data']['target'] = '#' . $this->options['id'];
            $options['data']['slide-to'] = $i;

            $content = isset($this->items[$i]['indicator']['content']) ? $this->items[$i]['indicator']['content'] : '';

            if ($i === 0) {
                Html::addCssClass($options, 'active');
            }
            $indicators[] = Html::tag('li', $content, $options);
        }

        $indicatorsOptions = $this->indicatorsOptions;
        $indicatorsTag = ArrayHelper::remove($indicatorsOptions, 'tag', 'ol');


        return Html::tag($indicatorsTag, implode("\n", $indicators), $indicatorsOptions);
    }
}