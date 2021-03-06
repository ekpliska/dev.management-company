<?php

    use yii\helpers\Html;
    use app\widgets\Slider;
    use app\assets\AppAsset;

AppAsset::register($this);

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
<body class="page_error">
<?php $this->beginBody() ?>
<main>
    <?= $content ?>
</main>    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>