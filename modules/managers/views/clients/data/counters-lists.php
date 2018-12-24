<?php

    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use app\models\TypeCounters;

/* 
 * Рендер вида Приборы учета
 */
    
$current_date = date('Y-m-d');
$array_image = [
    '1' => 'hold-water',
    '2' => 'hot-water',
    '3' => 'electric-meter',
    '4' => 'heating-meter',
    '5' => 'heat-distributor',
];    
?>

<?php if (isset($counters_lists) && is_array($counters_lists)) : // 1 ?>

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
        <td><?= Yii::$app->formatter->asDate($indication['Дата снятия показания'], 'long') ?></td>
        <td><?= $indication['Предыдущие показание'] ?></td>
                
        <?php if ($data_check == true) : // 2 ?>
        <td><span>Ввод показаний заблокирован</span></td>
        <td>
            <?php if (!ArrayHelper::keyExists($indication['Регистрационный номер прибора учета'], $not_verified)) : // 3 ?>
                <?= Html::button('Заказать поверку', [
                        'class' => 'create-send-request', 
                        'data-account' => $this->context->_choosing,
                        'data-counter-type' => $indication['Тип прибора учета'],
                        'data-counter-num' => $indication['Регистрационный номер прибора учета'],
                ]) ?>
            <?php else: // else if 3 ?>
                <span class="message-request">Заявка на поверку cформирована</span>
                <br />
                <?= Html::a('Мои платные услуги', ['paid-services/order-services']) ?>
            <?php endif; // end if 3 ?>
        </td>
        <?php else: // else if 2 ?>
        <td>
            <?php if ($indication['Текущее показание'] != null) : // 4 ?>
                <?= $indication['Текущее показание'] ?>
            <?php elseif ($is_btn) : // else if 4 ?>
                <?= $form->field($model_indication, "[{$indication['Регистрационный номер прибора учета']}]current_indication_repeat")
                        ->hiddenInput(['class' => 'reading-input', 'value' => $indication['Предыдущие показание'], 'disabled' => true])
                        ->label(false) ?>
                <?= $form->field($model_indication, "[{$indication['Регистрационный номер прибора учета']}]counter_number")
                        ->hiddenInput(['class' => 'reading-input', 'value' => $indication['Регистрационный номер прибора учета'], 'disabled' => true])
                        ->label(false) ?>
                <?= $form->field($model_indication, "[{$indication['Регистрационный номер прибора учета']}]current_indication")
                        ->input('text', ['class' => 'reading-input indication_val', 'disabled' => true])
                        ->label(false) ?>
            <?php endif; // end if 4 ?>
        </td>                
        <td>
            <?= $indication['Текущее показание'] ? $indication['Текущее показание'] - $indication['Предыдущие показание'] : '' ?>
        </td>
        <?php endif; // end if 2 ?>
    </tr>
    <?php endforeach; ?>
<?php else: // else if 1 ?>
    <tr>
        <td colspan="7" class="status-not-found">
            Показания приборов учета для текущего лицевого счета не найдены.
        </td>
    </tr>                
<?php endif; // else if 1 ?>
