<?php

    use yii\helpers\Html;
    use app\models\TypeCounters;

/* 
 * Вкладка "Приборы учета"
 */
$current_date = date('Y-m-d');
?>
<div class="table-container">
    <table class="table clients-table table-striped without-border">
        <thead>
            <tr>
                <th></th>
                <th>Прибор учета</th>
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
                        <td>
                            <?= Yii::$app->formatter->asDate($indication['Дата следующей поверки'], 'long') ?>
                        </td>
                        <td>
                            <?= strtotime($indication['Дата снятия показания']) > 0 
                                    ? Yii::$app->formatter->asDate($indication['Дата снятия показания'], 'long') 
                                    : '<span class="date">Дата передачи не установлена<span>' ?>
                        </td>
                        <td>
                            <?= $indication['Предыдущие показание'] ?>
                        </td>
                        <?php if ($data_check == true) : // Если дата поверки истекла, выводим соответсвующее сообщение ?>
                        <td colspan="2">
                            <span>Ввод показаний заблокирован</span>
                            <br />
                            <?= Html::a('Мои приборы учета', ['counters/index']) ?>
                        </td>
                        <?php elseif ($data_check == false && !empty($indication['Текущее показание'])) : // Выводим текущие показания и Расход ?>
                        <td>
                            <?= $indication['Текущее показание'] ?>
                        </td>
                        <td>
                            <span id="<?= "result-{$indication['ID']}" ?>">
                                <?= $indication['Текущее показание'] ? round($indication['Текущее показание'] - $indication['Предыдущие показание'], 2) : '' ?>
                            </span>
                        </td>
                        <?php elseif ($data_check == false && empty($indication['Текущее показание'])) : // Предлагаем ввести показания ?>
                        <td colspan="2">
                            <?= Html::a('Необходимо передать показания', ['counters/index'], ['class' => 'counters-info']) ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">
                        Показания за текущий приеод отсутствуют.
                    </td>
                </tr>                
            <?php endif; ?>
        </tbody>
    </table>
</div>