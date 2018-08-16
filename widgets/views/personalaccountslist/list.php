<?php

    use yii\helpers\Html;
    
/* 
 * Получить список все лицевых счетов пользователя
 */
?>

<li>
    <?= Html::beginForm(['/'], 'post') ?>
    <?= Html::dropDownList('current__account_list', null, $account_list, ['class' => 'form-control', 'style' => 'margin-top: 7px']) ?>
    <?= Html::endForm() ?>
</li>

