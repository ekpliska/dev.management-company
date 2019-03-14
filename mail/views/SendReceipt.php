<?php

/*
 * Отправка квитанции
 */
?>

<div>
    <p>Здравствуйте, <?= Yii::$app->userProfile->getFullNameClient() ?></p>
    <p>Ваша квитанция <?= isset($receipt_number) ? $receipt_number : '' ?>, находится во вложении в данном письме.</p>
    <p>Ваша компания, <?= Yii::$app->params['company-name'] ?></p>
</div>
