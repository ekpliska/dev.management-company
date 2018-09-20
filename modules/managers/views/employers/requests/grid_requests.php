<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Таблица
 * Все не закрытые заявки специалиста
 */

?>
<?php if (isset($requests) && $requests) : ?>
<table id="myTable">
    <tr class="header">
        <th style="width:20%;">Номер заявки</th>
        <th style="width:30%;">Дата назначения</th>
        <th style="width:10%;">Статус</th>
        <th style="width:10%;"></th>
    </tr>
    <?php foreach ($requests as $request) : ?>
        <tr>
            <td><?= $request['requests_ident'] ?></td>
            <td><?= FormatHelpers::formatDate($request['updated_at']) ?></td>
            <td><?= FormatHelpers::statusName($request['status']) ?></td>
            <td><?= Html::a('см.', ['/', 'id' => $request['requests_id']]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>