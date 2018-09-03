<?php
    
$this->title ="Главная страница"
?>

<div class="clients-default-index">
    
    <h1><?= $this->title ?></h1>
    <?php
        echo '<pre>';
        var_dump(Yii::$app->userProfile);
    ?>
    
</div>
