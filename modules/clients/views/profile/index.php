<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use app\modules\clients\widgets\SubMenuProfile;
    use app\modules\clients\widgets\AlertsShow;
    use app\modules\clients\widgets\ModalWindows;
    
/*
 * Профиль пользователя
 */
$this->title = 'Профиль абонента';
// Если пользователь Собственник, то меняем разметку блоков профиля
$col = Yii::$app->user->can('clients') ? 3 : 2;
?>

<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= SubMenuProfile::widget() ?>
    
    <?= AlertsShow::widget() ?>

    <?php
        $form = ActiveForm::begin([
            'id' => 'profile-form',
            'action' => ['profile/update-profile', 'form' => 'profile-form'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnChange' => true,
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
         ])
    ?>
    <div class="col-md-12 text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        <br /><br />
    </div>
    
    <!-- Block of customer -->
    <div class="col-md-<?= 12/$col ?>">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-left">
                    <strong>Контактные данные</strong>
                </div>
                <div class="text-right">
                    <?php if (isset($is_rent)) : ?>
                        <?= Html::checkbox('is_rent', $is_rent ? 'checked' : '', ['id' => 'is_rent']) ?> Арендатор
                    <?php endif; ?>
                </div>
            </div>            
            <div class="panel-body">                
                <div class="container-fluid">
                    <div class="row">
                        <?= $this->render('data/contact-info', ['user_info' => $user_info]); ?>                        
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
    <!-- End block of customer -->
    
    <!-- Block of avatar and notifications -->
    <div class="col-md-<?= 12/$col ?>">        
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Фотография</strong></div>
            <div class="panel-body">
                <div class="text-center">
                    <?= Html::img($user_info->photo, ['id' => 'photoPreview','class' => 'img-circle', 'alt' => $user_info->username, 'width' => 150]) ?>
                </div>
                    
                <?= $form->field($user, 'user_photo')->input('file', ['id' => 'btnLoad']) ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><strong>Оповещения</strong></div>
            <div class="panel-body">
                <?= $form->field($user, 'user_check_email')->checkbox() ?>
            </div>
        </div>
        
    </div>
    <!-- End block of avatar and notifications -->
    
    <?php if (Yii::$app->user->can('clients')) : ?>
    
        <div class="col-md-<?= 12/$col ?>">
            <?= Html::dropDownList('_list-account', 
                    $this->context->_choosing, 
                    $accounts_list, [
                        'class' => 'form-control', 
                        'id' => '_list-account',
                        'data-client' => $user_info->clientID]) 
            ?>
            <br />

            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Контактные данные арендатора</strong>                
                </div>
                <div class="panel-body info_rent">
                    <div id="content-replace">
                        <?php if (isset($is_rent) && $is_rent) : ?>
                            <?= $this->render('_form/rent-view', [
                                    'model_rent' => $model_rent]) 
                            ?>
                        <?php else : ?>
                            <div class="form-add-rent">
                                Арендатор отсутствует
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    

    
    <?= ModalWindows::widget(['modal_view' => 'changes_rent']) ?>
    
    <?php endif; ?>

    <?php ActiveForm::end(); ?>
    
</div>


<?php
$this->registerJs("
    $('#is_rent').on('change', function(e) {
        var rentsId = $('input[id=_rents]').val();
        if ($('input').is('#_rents')) {
            $('#changes_rent').modal('show');
            $.ajax({
                url: 'get-rent-info?rent=' + rentsId,
                method: 'POST',
                dataType: 'json',
                data: {
                    rent_id: rentsId,
                },
                success: function(response) {
                    if (response.status) {
                        $('#changes_rent #rent-surname').text(response.rent.rents_surname);
                        $('#changes_rent #rent-name').text(response.rent.rents_name);
                        $('#changes_rent #rent-second-name').text(response.rent.rents_second_name);
                    } else {
                        console.log('Error #1000-01');
                    }
                }
            });
        } else {
            // Показать форму Добавление нового арендатора
            if ($('#is_rent').is(':checked')) {
                $.ajax({
                    url: 'show',
                    method: 'POST',
                    data: {
                        accountNumber: '" . $accounts_info->account_number . "',
                        _show: $(this).val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.show) {
                            console.log(response.show);
                            $('.form-add-rent').html(response.data);
                        } else {
                            $('.form-add-rent').html('Арендатор отсутствует');
                        }
                    }
                });
            } else {
                $('.form-add-rent').html('Арендатор отсутствует');
            }
        }
    });    
")
?>