<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Модальное окно "Новый тариф"
 */
?>


    <div class="modal fade" id="create-new-rate" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-header">
                    <h4 class="modal-title">
                        Новый тариф
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="modal__text">
                        <?php
                            $form = ActiveForm::begin([
                                'id' => 'create-new-rate',
                            ]);
                        ?>                        

                        <?= $form->field($model, 'rates_name')->input('text')->label() ?>

                        <?= $form->field($model, 'rates_category_id')
                                ->dropDownList($service_categories, [
                                    'prompt' => 'Выбрать из списка...'])
                                ->label() ?>

                        <?= $form->field($model, 'rates_unit_id')
                                ->dropDownList($units, [
                                    'prompt' => 'Выбрать из списка...'
                                ])->label() ?>

                        <?= $form->field($model, 'rates_cost')->input($type)->label() ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
                    <?= Html::button('Отмена', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>


