<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
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

<?php if (!empty($counters_lists) && is_array($counters_lists)) : ?>
    <?php foreach ($counters_lists as $key => $indication) : ?>
        <?php $data_check = (strtotime($current_date) >= strtotime($indication['Дата следующей поверки'])) ? true : false ?>
        <tr class="<?= ($data_check == true) ? 'block-edit-reading' : '' ?>">
            <td class="image-counters">
                <?= Html::img('/images/counters/' . $array_image[TypeCounters::getTypeID($indication['Тип прибора учета'])] . '.svg', ['alt' => '']) ?>
            </td>
            <td>
                <p class="counter-name"><?= $indication['Тип прибора учета'] ?></p>
                <p class="counter-number"><?= $indication['Регистрационный номер прибора учета'] ?></p>
            </td>
            <td><?= Yii::$app->formatter->asDate($indication['Дата следующей поверки'], 'long') ?></td>
            <td><?= Yii::$app->formatter->asDate($indication['Дата снятия предыдущего показания'], 'long') ?></td>
            <td><?= $indication['Предыдущие показание'] ?></td>
            
            <?php if ($data_check == true) : ?>
            <td>
                <span>Ввод показаний заблокирован</span>
            </td>
            <td>
                <?php if ($is_current && !ArrayHelper::keyExists($indication['ID'], $auto_request)) : ?>
                    <?= Html::button('Заказать поверку', [
                            'id' => "create-request-{$key}",
                            'class' => 'create-send-request',
                            'data-account' => $account_number,
                            'data-counter-type' => $indication['Тип прибора учета'],
                            'data-counter-id' => $indication['ID']]) ?>
                
                <?php elseif ($is_current) : ?>
                    <span class="message-request">
                        <?php $reduest_number = ArrayHelper::getValue($auto_request, $indication['ID']) ?>
                        <?= Html::a("ID заявки {$reduest_number}", ['paid-requests/view-paid-request', 'request_number' => $reduest_number]) ?>
                    </span>
                <?php endif; ?>
            </td>
            <?php else: ?>
            <td>
            <?php if (!$is_current) : ?>
                <?= $indication['Текущее показание'] ?>
            <?php elseif ($is_current) : // Блок отправки показаний ?>
                <div class="input-indication">
                    <?= Html::input('text', "{$indication['ID']}_current_indication", $indication['Текущее показание'] ? $indication['Текущее показание'] : null, [
                            'id' => 'indication',
                            'class' => 'reading-input',
                            'data-unique-counter' => $indication['ID']]) ?>
                        
                    <label class="<?= "error-ind-{$indication['ID']}" ?>"></label>
                    <?= Html::button('Отправить', [
                            'id' => "send-indication-{$indication['ID']}",
                            'class' => 'btn btn-sm send-indication-counter',
                            'data' => [
                                'prev-indication' => $indication['Предыдущие показание'],
                                'unique-counter' => $indication['ID']],
                            'disabled' => true]) ?>
                    </div>
            <?php endif; // Конец блока отправки показаний ?>
            </td>                
            <td>
                <span id="<?= "result-{$indication['ID']}" ?>">
                    <?= $indication['Текущее показание'] ? $indication['Текущее показание'] - $indication['Предыдущие показание'] : '' ?>
                </span>
            </td>
        <?php endif; ?>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7">
            Ничего не найдено.
        </td>
    </tr>                
<?php endif; ?>