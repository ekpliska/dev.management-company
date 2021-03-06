<?php

    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use kartik\date\DatePicker;
    use yii\widgets\ActiveForm;

/* 
 * Профиль собсвенника, раздел Приборы устройства
 */

$this->title = 'Собственники';
$this->title = Yii::$app->params['site-name-manager'] .  'Собственники';
$this->params['breadcrumbs'][] = ['label' => 'Собственники', 'url' => ['clients/index']];
$this->params['breadcrumbs'][] = $client_info->fullName . ' [' . $account_choosing->account_number . ']';
?>

<div class="manager-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div class="profile-page">
        <?= $this->render('page-profile/header', [
                'user_info' => $user_info,
                'client_info' => $client_info,
                'list_account' => $list_account,
                'account_choosing' => $account_choosing,
        ]) ?>
        

        <div class="counters-page row">
            <div class="payments-date-block">
                <p class="payment_title"><i class="glyphicon glyphicon-calendar"></i> Период</p>
                <div id="panel-search">
                    <?= DatePicker::widget([
                            'name' => 'date_start-period-pay',
                            'class' => '__width-small',
                            'type' => DatePicker::TYPE_INPUT,
                            'language' => 'ru',
                            'options' => [
                                'placeholder' => Yii::$app->formatter->asDate(time(), 'LLLL-yyyy')
                            ],
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'MM-yyyy',
                            ]
                        ]);        
                    ?>
                    <?= Html::button('Показать', [
                            'id' => 'show-prev-indication',
                            'class' => 'show-indication btn-show-info',
                            'data' => [
                                'account' => $account_choosing->account_number,
                            ]
                        ]) ?>
                </div>
                <div class="col-md-12 message-block"></div>
            </div>            

            <table class="table managers-table managers-table-in table-striped">
                <thead>
                <tr>
                    <th></th>
                    <th>Приборы учета</th>
                    <th>Дата <br /> следующей поверки</th>
                    <th>Дата <br /> снятия показаний</t</th>
                    <th>Предыдущиее <br /> показания</t</th>
                    <th>Текущее показание</t</th>
                    <th>Расход</th>
                </tr>
                </thead>
                <tbody id="indication-table">
                    <?= $this->render('data/counters-lists', [
                            'counters_lists' => $counters_lists,
                            'is_current' => $is_current,
                            'auto_request' => $auto_request,
                            'account_id' => $account_choosing->account_id,
                    ]) ?>
                </tbody>
            </table>

        </div>
        
    </div>
    
    <div class="counters-notification">
        <?php if (isset($comment_counter) && !empty($comment_counter)) : ?>
            <?php 
                $comment_form = ActiveForm::begin([
                    'id' => 'counters-notification-form',
                    'validateOnChange' => false,
                    'validateOnBlur' => false,
                    'fieldConfig' => [
                        'template' => '<div class="field"></i>{label}{input}{error}</div>',
                    ],
                ]) ?>

                    <?= $comment_form->field($comment_counter, 'comments_title')
                            ->input('text', ['class' => 'field-input'])
                            ->label($comment_counter->getAttributeLabel('comments_title'), ['class' => 'field-label']) ?>

                    <?= $comment_form->field($comment_counter, 'comments_text', [
                                'template' => '<div class="field-page-textarea">{label}{input}{error}</div>'])
                            ->textarea(['rows' => 10, 'class' => 'field-input-textarea-page'])
                            ->label($comment_counter->getAttributeLabel('comments_text'), ['class' => 'field-label']) ?>

                    <div class="text-left">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn save-settings-small']) ?>
                        <?= Html::button('Удалить', [
                                'class' => 'btn delete-settings-small', 
                                'id' => 'delete-note-counters', 
                                'data-note' => $comment_counter->id]) ?>
                    </div>

                <?php ActiveForm::end(); ?>
        <?php else: ?>
        
            <?= Html::button('Добавить примечание', [
                    'class' => 'btn-show-info', 
                    'data-toggle' => 'modal', 
                    'data-target' => '#create-new-notification']) ?>
        
            <?= $this->render('_form/add-notification', [
                    'model_comment' => $model_comment,
                    'account_id' => $account_choosing->account_id
            ]) ?>
        
        <?php endif; ?>
        </div>
</div>