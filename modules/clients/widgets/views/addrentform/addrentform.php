<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
/*
 * Модальное окно
 * Форма - Добавить арендатора
 */
?>
<div id="add-rent-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close add-rent-modal__close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Новый арендатор</h4>
            </div>
            <div class="modal-body">
                <?php
                    $form_add_rent = ActiveForm::begin([
                        'id' => 'add-rent',
                        'validateOnSubmit' => true,
                        'validateOnBlur' => false,
                        'validateOnChange' => false,                        
                        'enableAjaxValidation' => true,                        
                        'validationUrl' => ['profile/validate-add-rent-form'],
                    ])
                ?>
                
                    <?= $form_add_rent->field($add_rent, 'account_id')->hiddenInput(['value' => $add_rent->account_id, 'id' => '_personal-account'])->label(false) ?>
                
                    <?= $form_add_rent->field($add_rent, 'rents_surname')
                            ->input('text', [
                                'placeHolder' => $add_rent->getAttributeLabel('rents_surname'),
                                'class' => 'form-control rents-surname'])
                            ->label() ?>

                    <?= $form_add_rent->field($add_rent, 'rents_name')
                            ->input('text', [
                                'placeHolder' => $add_rent->getAttributeLabel('rents_name'),
                                'class' => 'form-control rents-name'])
                            ->label() ?>

                    <?= $form_add_rent->field($add_rent, 'rents_second_name')
                            ->input('text', [
                                'placeHolder' => $add_rent->getAttributeLabel('rents_second_name'),
                                'class' => 'form-control rents-second-name'])
                            ->label() ?>

                    <?= $form_add_rent->field($add_rent, 'rents_mobile')
                            ->widget(MaskedInput::className(), [
                                'mask' => '+7(999) 999-99-99'])
                            ->input('text', [
                                'placeHolder' => $add_rent->getAttributeLabel('rents_mobile'),
                                'class' => 'form-control rents-mobile'])
                            ->label() ?>

                    <?= $form_add_rent->field($add_rent, 'rents_email')
                            ->input('text', [
                                'placeHolder' => $add_rent->getAttributeLabel('rents_email'),
                                'class' => 'form-control rents-email'])
                            ->label() ?>

                    <?= $form_add_rent->field($add_rent, 'password')
                            ->input('password', [
                                'placeHolder' => $add_rent->getAttributeLabel('password'),
                                'class' => 'form-control rents-hash show_password'])
                            ->label() ?>                                   
                    
                    <?= Html::checkbox('show_password_ch', false) ?> <span class="show_password__text">Показать пароль</span>
                
            </div>
            <div class="modal-footer">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-success btn__add_rent']) ?>
                <button type="button" class="btn btn-default add-rent-modal__close" data-dismiss="modal">Отмена</button>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
