<?php

    use yii\bootstrap\ActiveForm;
    use kartik\date\DatePicker;
    use yii\helpers\Html;

/* 
 * Форма поиска
 */
?>
<?php 
    $form = ActiveForm::begin([
        'id' => 'search-paid-request-form',
        'action' => ['index', 'block' => 'paid-requests'],
        'method' => 'get',
        'fieldConfig' => [
            'template' => '{input}',
        ],
]); ?>

        <?= $form->field($search_model, 'services_servise_category_id')
                ->dropDownList($servise_category, [
                    'prompt' => '[Категория]',
                    'class' => '']) ?>

        <?= $form->field($search_model, 'services_name_services_id')
                ->dropDownList($name_services, [
                    'prompt' => '[Услуга]',
                    'class' => '']) ?>

        <?= $form->field($search_model, 'services_specialist_id')
                ->dropDownList($specialist_lists, [
                    'prompt' => '[Специалист]',
                    'class' => '']) ?>

        <?= $form->field($search_model, 'status')
                ->dropDownList($status_list, [
                    'prompt' => '[Статус]',
                    'class' => '']) ?>

        <fieldset class="report__period">
            <legend class="report__period-border">Период</legend>
                <?= $form->field($search_model, 'date_start')
                    ->widget(DatePicker::className(), [
                        'id' => 'date-start',
                        'language' => 'ru',
                        'options' => [
                            'placeHolder' => 'Выберите дату с',
                            'class' => 'search-date-report',
                        ],
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoClose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]])
                    ->label(false) ?>

                <?= $form->field($search_model, 'date_finish')
                    ->widget(DatePicker::className(), [
                        'id' => 'date-finish',
                        'language' => 'ru',
                        'options' => [
                            'placeHolder' => 'Выберите дату по',
                            'class' => 'search-date-report',
                        ],
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoClose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]])
                    ->label(false) ?>
        </fieldset>

    <?= Html::submitButton('Найти', ['class' => '']) ?>
    <?= Html::resetButton('Сброс', ['class' => '']) ?>

<?php ActiveForm::end(); ?>