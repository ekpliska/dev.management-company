<?php

    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\helpers\Html;

/* 
 * Модальное окно "Создание заявки"
 */

?>
<div class="modal fade" id="create-new-requests" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="modal-title">Новая заявка</h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    <?php
                        $form = ActiveForm::begin([
                            'id' => 'create-new-request',
                            'action' => ['create-request'],
                            'enableAjaxValidation' => true,
                            'validationUrl' => ['validation-form'],
                        ])
                    ?>

                    <?= $form->field($model, 'category_service')
                            ->dropDownList($service_categories, [
                                'prompt' => 'Выбрать из списка...'])
                            ->label() ?>

                    <?= $form->field($model, 'service_name')
                            ->dropDownList($service_categories)
                            ->label() ?>

                    <?= $form->field($model, 'phone')
                            ->widget(MaskedInput::className(), [
                                'mask' => '+7 (999) 999-99-99'])
                            ->input('text', [
                                'placeHolder' => $model->getAttributeLabel('phone')])
                            ->label() ?>

                    <?= $form->field($model, 'fullname')
                            ->input('text', [
                                'placeHolder' => $model->getAttributeLabel('fullname')])
                            ->label() ?>

                    <?= $form->field($model, 'description')->textarea()->label() ?>

                    <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>

                    <?php ActiveForm::end() ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>