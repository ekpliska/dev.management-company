<?php

    use yii\helpers\Html;
    use app\models\StatusRequest;

/*
 * Вид статусов заявок
 */
?>

<div class="status-request">
    <ul class="nav nav-pills">
        <li>
            <?= Html::a('Все Заявки', ['requests/index'], ['class' => 'req-bange req-bange-default', 'data-status' => '-1']) ?>
        </li>
        <?php foreach ($status_requests as $key => $status) : ?>
        <li>
            <?= Html::a($status['name'], ['requests/index'], ['class' => $css_classes[$key] . ' status_request-switch', 'data-status' => $key]) ?>
            <?= $key != StatusRequest::STATUS_CLOSE && $status['count'] != 0 ? '<span class="count-label">' . $status['count'] . '</span>' : '' ?>
        </li>
        <?php endforeach; ?>
  </ul>
</div>