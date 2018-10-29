<?php
    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\bootstrap4\Modal;
    use yii\helpers\Url;
    use app\modules\clients\widgets\StatusRequest;
    use app\modules\clients\widgets\AlertsShow;
    
/* 
 * Заявки (Общая страница)
 */
$this->title = 'Мои заявки';
?>



<?= StatusRequest::widget() ?>

<div class="table-container">
    <div class="account-info-table-container req-table-container">
        
        <?= $this->render('data/grid', ['all_requests' => $all_requests]); ?>
        
        <div class="fixed-bottom req-fixed-bottom-btn-group mx-auto ">
            <?= Html::button('', ['class' => 'add-req-fixed-btn btn-link', 'data-toggle' => 'modal', 'data-target' => '#add-account-modal']) ?>
        </div>
    </div>
</div>

<?php /*
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <div class="col-md-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-account-modal">Добавить заявку</button>
        <h4>Статусы заявок</h4>
        
        <div class="list-group">
            <a href="#" class="list-group-item active" data-status="-1">Все</a>
            
            <?php foreach ($status_requests as $key => $status) : ?>
                <a href="<?= Url::to(['requests/index']) ?>" class="list-group-item" data-status="<?= $key ?>"><?= $status ?></a>
            <?php endforeach; ?>
                
        </div>
        
    </div>
    <div class="col-md-9">
        
        <?= AlertsShow::widget() ?>
        
        <?= $this->render('form/_filter', ['model_filter' => $model_filter, 'type_requests' => $type_requests]); ?>
        <?= $this->render('data/grid', ['all_requests' => $all_requests]); ?>

    </div>
</div>
*/ ?>


<?php
    Modal::begin([
        'id' => 'add-account-modal',
        'title' => 'Новая заявка',
        'closeButton' => [
            'class' => 'close add-acc-modal-close-btn req',
        ],
    ]);
?>
    <?php
        $form = ActiveForm::begin([
            'id' => 'add-request',
            'options' => [
                'enctype' => 'multipart/form-data',
            ]
        ]);
    ?>
                
        <?= $form->field($model, 'requests_type_id')
                ->dropDownList($type_requests, [
                    'prompt' => 'Выберите вид заявки из списка...'])->label(false) ?>

        <?= $form->field($model, 'requests_phone')
                ->widget(MaskedInput::className(), [
                    'mask' => '+7 (999) 999-99-99'])
                ->input('text', [
                    'placeHolder' => $model->getAttributeLabel('requests_phone'),])
                ->label(false) ?>

        <?= $form->field($model, 'requests_comment')->textarea(['rows' => 10])->label(false) ?>

        <?= $form->field($model, 'gallery[]')->input('file', ['multiple' => true])->label(false) ?>

        <div class="modal-footer no-border">
            <?= Html::submitButton('Отправить', ['class' => 'btn blue-outline-btn white-btn mx-auto']) ?>
            <?= Html::submitButton('Отмена', ['class' => 'btn red-outline-btn bt-bottom2 request__btn_close', 'data-dismiss' => 'modal']) ?>
        </div>    
    
    <?php ActiveForm::end(); ?>

<?php
    Modal::end();
?>

<?php /*
<div id="add-account-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Создать заявку</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <?php
                        $form = ActiveForm::begin([
                            'id' => 'add-request',
                            'options' => [
                                'enctype' => 'multipart/form-data',
                            ]
                        ])
                    ?>
                        <?= $form->field($model, 'requests_type_id')
                                ->dropDownList($type_requests,
                                        ['prompt' => 'Выберите вид заявки из списка']) ?>
                    
                        <?= $form->field($model, 'requests_phone')
                                ->widget(MaskedInput::className(), [
                                    'mask' => '+7 (999) 999-99-99',
                                ])
                                ->input('text', ['placeHolder' => $model->getAttributeLabel('requests_phone')])->label() ?>
                    
                        <?= $form->field($model, 'requests_comment')
                                ->textarea(['rows' => 10]) ?>
                    
                        <?= $form->field($model, 'gallery[]')->input('file', ['multiple' => true])->label() ?>
                </div>
            </div>
            <div class="modal-footer">
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-danger']) ?>
                    <?= Html::submitButton('Отмена', ['class' => 'btn btn-default request__btn_close', 'data-dismiss' => 'modal']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

*/ ?>

<?php
/* Фильтр заявок пользователя по 
 * ID лицевого счета, типу и статусу заявки
 */
$this->registerJs("    
    $('#account_number, .current__account_list').on('change', function(e) {
        
        e.preventDefault();
        
        var type_id = $('#account_number').val();
        var account_id = $('.current__account_list').val();
        var status = $('.list-group-item.active').data('status');

        $.ajax({
            url: 'filter-by-type-request?type_id=' + type_id + '&account_id=' + account_id + '&status=' + status,
            method: 'POST',
            data: {
                type_id: type_id,
                account_id: account_id,
                status: status,
            },
            success: function(data){
                if (data.status === false) {
                    console.log('Ошибка при передаче данных');
                } else {
                    $('.grid-view').html(data);
                }
            }
        });
    });
");
?>