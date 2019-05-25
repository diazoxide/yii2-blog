<?php

use diazoxide\blog\Module;

/** @var String $title */
/** @var \yii\db\ActiveQuery $featuredCategories */
$this->title = $title;

?>
<div id="blog-container">

    <div class="col-md-8 col-md-push-4 home-slider-container">
        <h1><?= $title ?></h1>
        <h3><? Module::t('', 'You can customize this page from administrator dashboard.') ?></h3>
    </div>

</div>