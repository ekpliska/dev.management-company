<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
?>

<div class="request-search">
    <?php $form = ActiveForm::begin([
        'id' => 'filter-form',
        'options' => ['data-pjax' => true],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model_filter, '_value')
            ->dropDownList($type_requests, [
                'id' => 'account_number',
                'prompt' => 'Все заявки'])
            ->label('Вид заявки') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJS("
    $('#account_number').on('change', function(){
        var type_id = $(this).val();
        if (type_id === null) {
            alert(type_id);
        }
        $.ajax({
            url:'" . Url::toRoute(['requests/filter-by-type-request']) . "',
            method: 'POST',
            data: {
                rent_id: type_id,
            },
            success: function(data){
                // console.log(data);
                $('.grid-view').html(data);
            }
        });
    });
        ")
?>