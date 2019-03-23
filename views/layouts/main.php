<?php

    use yii\helpers\Html;
    use app\widgets\Slider;
    use app\assets\AppAsset;
    use app\assets\CssLoginForm;

AppAsset::register($this);
CssLoginForm::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon ico -->
    <link type="image/png" href="/images/favicon_16x16.png" rel="icon" sizes="16x16">
    <link type="image/png" href="/images/favicon_32x32.png" rel="icon" sizes="32x32">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="main">
    <div class="row">
        <div class="col-lg-6 col-md-6 hidden-sm hidden-xs">
            <div class="general-slider">
                <?= Slider::widget() ?>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="general-right-block">
                <?= $content ?>
            </div>
        </div>
    </div>
    
</div>    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>