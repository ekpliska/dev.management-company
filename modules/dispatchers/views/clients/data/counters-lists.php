<?php

    use yii\helpers\Html;
    use app\models\TypeCounters;

/* 
 * Рендер вида Приборы учета
 */

// Текущая дата    
$current_date = date('Y-m-d');

// Массив изображений приборов учета, по ID типа прибора учета
$array_image = [
    '1' => 'hold-water',
    '2' => 'hot-water',
    '3' => 'electric-meter',
    '6' => 'electric-meter',
    '4' => 'heating-meter',
    '5' => 'heat-distributor',
];
?>

<?php if (isset($counters_lists) && is_array($counters_lists)) : // 1 ?>
<?php foreach ($counters_lists as $key => $indication) : ?>
<?php $data_check = (strtotime($current_date) >= strtotime($indication['Дата следующей поверки'])) ? true : false ?>
<tr class="<?= ($data_check == true) ? 'block-edit-reading' : '' ?>">
    <td>
        <?= Html::img('/images/counters/' . $array_image[TypeCounters::getTypeID($indication['Тип прибора учета'])] . '.svg', ['alt' => '']) ?>
    </td>
    <td>
        <p class="counter-name"><?= $indication['Тип прибора учета'] ?></p>
        <p class="counter-number"><?= $indication['Регистрационный номер прибора учета'] ?></p>
    </td>
    <td><?= Yii::$app->formatter->asDate($indication['Дата следующей поверки'], 'long') ?></td>
    <td><?= Yii::$app->formatter->asDate($indication['Дата снятия предыдущего показания'], 'long') ?></td>
    <td>
        <?= $indication['Предыдущие показание'] ?>
    </td>
    <?php if ($data_check == true) : ?>
        <td>
            <span>Ввод показаний заблокирован</span>
        </td>
    <?php else: ?>
        <td>
            <?= $indication['Текущее показание'] ?>
        </td>
    <?php endif; ?>            
        
    <td><?= $indication['Расход'] ?></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>