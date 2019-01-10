<?php
    
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use app\models\TypeCounters;
    
/* 
 * Вывод таблицы показания приборов учета
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

<div id="indication-table">
    <table class="table clients-table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Приборы учета</th>
                <th>Дата <br /> следующей поверки</th>
                <th>Дата <br /> снятия показаний</th>
                <th>Предыдущиее <br /> показания</th>
                <th>Текущее показание</th>
                <th>Расход</th>
            </tr>
        </thead>            
        <tbody>
            <?php if (isset($indications) && is_array($indications)) : ?>
                <?php foreach ($indications as $key => $indication) : ?>
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
                        <?php if ($data_check == true) : ?>
                            <td>
                                <span>Ввод показаний заблокирован</span>
                            </td>
                            <td>
                                <?php if (!ArrayHelper::keyExists($indication['Регистрационный номер прибора учета'], $counter_request)) : ?>
                                <?= Html::button('Заказать поверку', [
                                        'class' => 'create-send-request', 
                                        'data-account' => $this->context->_current_account_id,
                                        'data-counter-type' => $indication['Тип прибора учета'],
                                        'data-counter-num' => $indication['Регистрационный номер прибора учета'],
                                ]) ?>
                                <?php else: ?>
                                    <span class="message-request">
                                        Заявка на поверку cформирована
                                    </span>
                                    <br />
                                    <?= Html::a('Мои платные услуги', ['paid-services/order-services']) ?>
                                <?php endif; ?>
                            </td>
                        <?php else: ?>
                            <td>
                                <?php if ($indication['Текущее показание'] != null) : ?>
                                    <?= $indication['Текущее показание'] ?>
                                <?php elseif ($is_btn) : ?>
                                    <?= $form->field($model_indication, "[{$indication['Регистрационный номер прибора учета']}]current_indication_repeat")
                                            ->hiddenInput(['class' => 'reading-input', 'value' => $indication['Предыдущие показание'], 'disabled' => true])
                                            ->label(false) ?>
                                    <?= $form->field($model_indication, "[{$indication['Регистрационный номер прибора учета']}]counter_number")
                                            ->hiddenInput(['class' => 'reading-input', 'value' => $indication['Регистрационный номер прибора учета'], 'disabled' => true])
                                            ->label(false) ?>
                                    <?= $form->field($model_indication, "[{$indication['Регистрационный номер прибора учета']}]current_indication")
                                            ->input('text', ['class' => 'reading-input indication_val', 'disabled' => true])
                                            ->label(false) ?>
                                <?php endif; ?>
                            </td>                
                            <td>
                                <?= $indication['Текущее показание'] ? $indication['Текущее показание'] - $indication['Предыдущие показание'] : '' ?>
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
        </tbody>
    </table>
</div>
