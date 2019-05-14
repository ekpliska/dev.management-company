<?php
    use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
    <style type="text/css">
	.view-news {
            padding: 8px 12px;
            border: 1px solid #331fb6;
            border-radius: 25px;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #331fb6; 
            text-transform: uppercase;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
        }
    </style>
    
</head>
<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
