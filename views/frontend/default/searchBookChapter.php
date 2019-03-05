<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;
use yii\widgets\ListView;

\diazoxide\blog\assets\AppAsset::register($this);

$this->title = "Search";
?>

<div class="blog-post-book-chapter-search">

    <div class="container">
        <?= $this->render('_book_chapter_search_form',['model'=>$searchModel]) ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_chapter',
                    'itemOptions'=>[
                            'class'=>'col-sm-3 top-buffer-20'
                    ],
                    'layout' => '{items}{pager}{summary}'
                ]);
                ?>
            </div>
        </div>
    </div>
</div>


