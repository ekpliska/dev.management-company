<?php
    
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use app\models\TypeCounters;
    
/* 
 * Вывод таблицы показания приборов учета
 */
    
$current_date = date('Y-m-d');
?>

<div id="indication-table table-container">
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
            <?php if (!empty($indications) && is_array($indications)) : ?>
                <?php foreach ($indications as $key => $indication) : ?>
                    <?php $data_check = (strtotime($current_date) >= strtotime($indication['Дата следующей поверки'])) ? true : false ?>
                    <tr class="<?= ($data_check == true) ? 'block-edit-reading' : '' ?>">
                        <td class="image-counters">
                            <?= Html::img(TypeCounters::getImageCounter($indication['Тип прибора учета']), ['alt' => '']) ?>
                        </td>
                        <td>
                            <p class="counter-name"><?= $indication['Тип прибора учета'] ?></p>
                            <p class="counter-number"><?= $indication['Регистрационный номер прибора учета'] ?></p>
                        </td>
                        <td><?= Yii::$app->formatter->asDate($indication['Дата следующей поверки'], 'long') ?></td>
                        <td>
                            <?= strtotime($indication['Дата снятия показания']) > 0
                                    ? Yii::$app->formatter->asDate($indication['Дата снятия показания'], 'long') 
                                    : '<span class="date">Дата передачи не установлена<span>' ?>
                        </td>
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
                                        'data-account' => $this->context->_current_account_id,
                                        'data-counter-type' => $indication['Тип прибора учета'],
                                        'data-counter-id' => $indication['ID'],
                                ]) ?>
                                <?php elseif ($is_current == false) : ?>
                                    <?= Html::a('Мои платные услуги', ['paid-services/order-services']) ?>
                                <?php else: ?>
                                    <span class="message-request">
                                        ID заявки <?= ArrayHelper::getValue($auto_request, $indication['ID']) ?>
                                    </span>
                                    <br />
                                    <?= Html::a('Мои платные услуги', ['paid-services/order-services']) ?>
                                <?php endif; ?>
                            </td>
                        <?php else: ?>
                            <td>
                                <?php if (!$is_current) : ?>
                                    <?= $indication['Текущее показание'] ?>
                                <?php elseif ($is_current) : ?>
                                
                                    <div class="input-indication">
                                    <?= Html::input('text', "{$indication['ID']}_current_indication", $indication['Текущее показание'] ? $indication['Текущее показание'] : null , [
                                            'id' => 'indication',
                                            'class' => 'reading-input',
                                            'data-unique-counter' => $indication['ID'],
                                        ]) ?>
                                    <label class="<?= "error-ind-{$indication['ID']}" ?>"></label>
                                    <?= Html::button('Отправить', [
                                            'id' => "send-indication-{$indication['ID']}",
                                            'class' => 'btn btn-sm send-indication-counter',
                                            'data' => [
                                                'prev-indication' => $indication['Предыдущие показание'],
                                                'unique-counter' => $indication['ID'],
                                            ],
                                            'disabled' => true,
                                        ]) ?>
                                    </div>
                                
                                <?php endif; ?>
                            </td>                
                            <td>
                                <span id="<?= "result-{$indication['ID']}" ?>">
                                    <?= $indication['Текущее показание'] ? round($indication['Текущее показание'] - $indication['Предыдущие показание'], 2) : '' ?>
                                </span>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">
                        Показания за текущий период отсутствуют.
                    </td>
                </tr>                
            <?php endif; ?>
        </tbody>
    </table>    
</div>
