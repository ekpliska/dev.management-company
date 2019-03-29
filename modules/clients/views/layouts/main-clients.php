<?php

    use yii\helpers\Html;
    use app\assets\AppAsset;
    use app\assets\ClientsAsset;
    use yii\widgets\Breadcrumbs;

AppAsset::register($this);
ClientsAsset::register($this);

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
        <?php $this->beginContent('@app/modules/clients/views/layouts/header.php') ?>
        <?php $this->endContent() ?>        
        <div class="container container-full-client">
            <div class="row content">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 menu-clients">
                    #MENU
                </div>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>

    <?php $this->beginContent('@app/modules/clients/views/layouts/footer.php') ?>
    <?php $this->endContent() ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
