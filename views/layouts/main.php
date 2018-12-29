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
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<main role="main">
    
    <div class="col-md-6 general-slider">
        <?= Slider::widget() ?>
    </div>
    <div class="col-md-6 general-right-block">
        <?= $content ?>
    </div>
    
</main>    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>