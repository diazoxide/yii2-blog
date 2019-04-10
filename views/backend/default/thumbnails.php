<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 10.04
 * Time: 13:08
 */

use yii\helpers\Url;

/** @var \yii\web\View $this */
$this->title = \diazoxide\blog\Module::t(null, 'Regenerate Thumbnails');
?>
<div class="container">
    <h1><?= \diazoxide\blog\Module::t(null, 'You can regenerate post thumbnails.') ?></h1>
    <h4><?= \diazoxide\blog\Module::t(null, 'Posts count: ') . $count ?></h4>

    <div>
        <?= \yii\helpers\Html::a('Start Regeneration', '#', ['class' => 'btn btn-danger', 'id' => 'start-regeneration']) ?>
    </div>

    <label for="logs"><?= \diazoxide\blog\Module::t(null, 'Logs'); ?></label>
    <textarea class="form-control" id="logs" readonly></textarea>

    <?php
    $url = Url::to(['regenerate-thumbnails']);
    $js = <<<JS
        (function ($) {
            function re(step) {
                var limit = 50;
                var offset = step * limit;
                var url = "{$url}?limit=" + limit + "&offset=" + offset;
                
                var logs = $('#logs');
                $.ajax({
                    type: "POST",
                    url: url,
                    success: function (data, status) {
                        logs.append(data);
                        logs.scrollTop(logs[0].scrollHeight - logs.height());

                        step++;
                        re(step);
                    },
                    error: function(){
                        logs.append("Error.\\n");
                        step++;
                        re(step);
                    }
                });
            }

            $('#start-regeneration').click(function () {
                re(0);
            })
        })(jQuery);
JS;
    $this->registerJs($js);
    ?>
</div>

