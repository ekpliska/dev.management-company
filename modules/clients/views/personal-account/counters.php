<?php

    use kartik\date\DatePicker;
    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\models\TypeCounters;

/* 
 * Приборы учета
 */

$current_date = date('Y-m-d');
$array_image = [
    '1' => 'hold-water',
    '2' => 'hot-water',
    '3' => 'electric-meter',
    '4' => 'heating-meter',
    '5' => 'heat-distributor',
];
    
$this->title = Yii::$app->params['site-name'] . 'Показания приборов учета';
$this->params['breadcrumbs'][] = ['label' => 'Лицевой счет', 'url' => ['personal-account/index']];
$this->params['breadcrumbs'][] = 'Показания приборов учета';
?>

<?= Breadcrumbs::widget([
        'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'options' => [
            'class' => 'breadcrumb breadcrumb-padding'
        ],
]) ?>

<div class="counters-page row">

    <div class="col-md-3">
        <p class="payment_title"><i class="glyphicon glyphicon-calendar"></i> Период</p>
        <?= DatePicker::widget([
                'name' => 'date_start-period-pay',
                'type' => DatePicker::TYPE_INPUT,
                'value' => date('M-Y'),
                'language' => 'ru',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'M-yyyy'
                ]
            ]);        
        ?>        
    </div>
    <div class="col-md-9 text-right">
        <?= Html::button('Внести измнения', ['class' => 'btn-edit-reading']) ?>
        <?= Html::button('Сохранить', ['class' => 'btn-save-reading']) ?>
    </div>
    
    <?php if (isset($indications) && is_array($indications)) : ?>
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
                            <?= Html::a('Заказать поверку', ['/'], ['class' => 'create-send-request']) ?>
                        </td>
                    <?php else: ?>
                        <td>
                            <?php if ($indication['Текущее показание'] != null) : ?>
                                <?= $indication['Текущее показание'] ?>
                            <?php else: ?>
                                <?= Html::input('text', 'reading-input', '', ['class' => 'reading-input']) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $indication['Текущее показание'] ? $indication['Текущее показание'] - $indication['Предыдущие показание'] : '' ?>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <div class="col-md-12 counters-message">
        <p class="title">Обратите внимание</p>
        <p>#TODO</p>
    </div>
    
</div>