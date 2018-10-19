<?php

    use app\modules\clients\widgets\News;

$this->title ="Главная страница"
?>

<div class="clients-default-index">
    
    <h1><?= $this->title ?></h1>
    
    <?= 
        News::widget([
            'estate_id' => Yii::$app->userProfile->_user['estate_id'], 
            'house_id' => Yii::$app->userProfile->_user['house_id'], 
            'flat_id' => Yii::$app->userProfile->_user['flat_id']]) 
    ?>
    
</div>