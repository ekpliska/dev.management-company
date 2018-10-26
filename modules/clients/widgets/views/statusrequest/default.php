<?php

    use yii\helpers\Html;

/* 
 * Вид статусов заявок
 */

?>
<nav class="navbar navbar-dark menu-collapsed-nav justify-content-between navbar-expand-sm p-0 carousel-item d-block pay-panel mx-auto req-nav">
    <ul class="nav nav-pills mx-auto justify-content-start px-3">
        <li class="nav-item">
            <?= Html::a('Все Заявки', ['/'], ['class' => 'btn req-btn req-btn-all', 'data-status' => $key]) ?>
        </li>
        <?php foreach ($status_requests as $key => $status) : ?>
            <li class="nav-item">
                <?= Html::a($status, ['requests/index'], ['class' => $css_classes[$key], 'data-status' => $key]) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>