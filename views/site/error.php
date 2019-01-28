<?php

    use yii\helpers\Html;
    
    
/*
 * Страница обработки ошибок
 */
$this->title = '404 Вы обратились к несуществующей странице';
?>

<div class="error-page__image">
    
    <h1 class="error-page__title"><?= $message ?></h1>
    
</div>
<?= Html::a('Перейти на главную', ['/'], ['class' => 'btn btn-error-page']) ?>
