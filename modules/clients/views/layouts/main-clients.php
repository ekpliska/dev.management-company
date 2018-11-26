<?php

    use yii\helpers\Html;
    use app\assets\AppAsset;
    use app\assets\ClientsAsset;

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
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <?php $this->beginContent('@app/modules/clients/views/layouts/header.php') ?>
    <?php $this->endContent() ?>    
        
    <div class="container">
        <?= $content ?>
    </div>
        
    <?php // $this->beginContent('@app/modules/clients/views/layouts/footer.php') ?>
    <?php // $this->endContent() ?>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
