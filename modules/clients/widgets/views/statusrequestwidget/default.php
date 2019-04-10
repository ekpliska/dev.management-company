<?php

    use yii\helpers\Html;
    use app\models\StatusRequest;

/* <li><a href="#">Menu 1</a></li>
        <li><a href="#">Menu 2</a></li>
        <li><a href="#">Menu 3</a></li>
      </ul>
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
        </li>
        <?php endforeach; ?>
  </ul>
</div>

<?php /* if (Yii::$app->controller->id == 'requests' && Yii::$app->controller->action->id == 'index') : ?>
<div class="status-request">
    <ul class="nav nav-pills nav-justified status-request">
        <li>
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
<?php endif; */ ?>