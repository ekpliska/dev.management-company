<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\assets\UnsubscribeAsset;

UnsubscribeAsset::register($this);

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
<main>
    <div class="sub_bar">
        <div class="container text-center">
            <a href="<?= Url::to(['site/index']) ?>">
                <?= Html::img('/images/navbar/group_46.svg', ['alt' => 'ELSA Company'])  ?>
            </a>
        </div>
    </div>
    <?= $content ?>
</main>    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>