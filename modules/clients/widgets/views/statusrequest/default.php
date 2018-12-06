<?php

    use yii\helpers\Html;
    use app\models\StatusRequest;

/* 
 * Вид статусов заявок
 */

?>
<?php if (Yii::$app->controller->id == 'requests' && Yii::$app->controller->action->id == 'index') : ?>
    <div class="container-fluid navbar_repusets text-center">
        <ul class="nav nav-pills status-request">
            <li class="nav-item">
                <?= Html::a('Все Заявки', ['requests/index'], ['class' => 'req-bange req-bange-default', 'data-status' => '-1']) ?>
            </li>
            <?php foreach ($status_requests as $key => $status) : ?>
                <li class="nav-item">
                    <?= Html::a($status['name'], ['requests/index'], ['class' => $css_classes[$key] . ' status_request-switch', 'data-status' => $key]) ?>
                    <?= $key != StatusRequest::STATUS_CLOSE && $status['count'] != 0 ? '<span class="count-label">' . $status['count'] . '</span>' : '' ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>