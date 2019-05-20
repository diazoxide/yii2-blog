<?php

use diazoxide\blog\assets\DynamicFrontendAsset;
use diazoxide\blog\components\ViewPatternHelper;
use yii\helpers\Html;

/*
 * Registering Dynamic frontend assets
 * */
DynamicFrontendAsset::register( $this );
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<!--Head begin-->
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode( $this->title ) ?></title>
	<?php $this->head() ?>
	<?php Yii::$app->website->register(); ?>
</head>
<!--Head end-->

<!--Body begin-->
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div id="main-content">

		<?php
		/** @var \yii\web\View $this */

		$pattern = $this->context->module->getLayoutPattern();

        /** @var array $_params_ */
		echo ViewPatternHelper::extract($pattern,$_params_)

		?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
<!--Body end-->

</html>
<?php $this->endPage() ?>
