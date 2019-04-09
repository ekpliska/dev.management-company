<?php

    use yii\helpers\Html;

/* 
 * Слайдер для текущих показаний приборов учета
 */
?>
<?php // echo '<pre>'; var_dump($indications) ?>
<?php if (!empty($indications) && is_array($indications)) : ?>
<div class="counters-carousel owl-carousel owl-theme">
    <?php foreach ($indications as $key => $indication) : ?>
    <div class="item">
        <?= Html::input('text', ['value' => $indication['Текущие показания']]) ?>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>