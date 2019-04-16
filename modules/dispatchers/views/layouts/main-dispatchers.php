<?php

    use yii\helpers\Html;
    use app\assets\AppAsset;
    use app\assets\DispatchersAsset;

AppAsset::register($this);
DispatchersAsset::register($this);

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
    <div class="wrap">        
        <?php $this->beginContent('@app/modules/dispatchers/views/layouts/header.php'); ?>
        <?php $this->endContent(); ?> 
        <div class="container container-full">
            <?= $content ?>
        </div>        
    </div>
    <?php $this->endBody(); ?>
    
</body>
</html>
<?php $this->endPage() ?>