<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\helpers\StatusHelpers;

/* 
 * Рендер виджета для формирования заявко закрепленных за специалистом 
 */
?>
<div class="row col-md-6">
    <h3 class="title-type-request">Заявки</h3>
    <?php if (!empty($requests_list) && count($requests_list) > 0) : ?>
    <table class="table managers-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Вид заявки</th>
                <th>Дата постановки</th>
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests_list as $request) : ?>
            <tr>
                <td><?= Html::a($request['requests_ident'], ['paid-requests/view-paid-request', 'request_number' => $request['requests_ident']]) ?></td>
                <td><?= $request['type_requests_name'] ?></td>
                <td><?= FormatHelpers::formatDate($request['created_at'], false, 0, false) ?></td>
                <td><?= StatusHelpers::requestStatus($request['status']) ?></td>
            </tr>
            <?php endforeach ;?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="notice info">
            <p>Специалист назначенных заявок не имеет.</p>
        </div>
    <?php endif; ?>
</div>

<div class="col-md-6">
    <h3 class="title-type-request">Платные услуги</h3>
    <?php if (!empty($paid_requests_list) && count($paid_requests_list) > 0) : ?>
    <table class="table managers-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Категория <br /> наименование</th>
                <th>Дата постановки</th>
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paid_requests_list as $paid_request) : ?>
            <tr>
                <td><?= Html::a($paid_request['services_number'], ['paid-requests/view-paid-request', 'request_number' => $paid_request['services_number']]) ?></td>
                <td><?= $paid_request['category_name'] . '<br />' . $paid_request['services_name'] ?></td>
                <td><?= FormatHelpers::formatDate($paid_request['created_at'], false, 0, false) ?></td>
                <td><?= StatusHelpers::requestStatus($paid_request['status']) ?></td>
            </tr>
            <?php endforeach ;?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="notice info">
            <p>Специалист назначенных заявок не имеет.</p>
        </div>
    <?php endif; ?>
</div>