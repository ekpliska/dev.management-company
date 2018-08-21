<?php
    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\helpers\Url;
    
/* 
 * Заявки (Обзая страница)
 */
$this->title = 'Мои заявки';
?>
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
        
        <?php if (Yii::$app->session->hasFlash('success')) : ?>
            <div class="alert alert-info" role="alert">
                <strong>
                    <?= Yii::$app->session->getFlash('success', false); ?>
                </strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>                    
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('error')) : ?>
            <div class="alert alert-error" role="alert">
                <strong>
                    <?= Yii::$app->session->getFlash('error', false); ?>
                </strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>                
            </div>
        <?php endif; ?>         
        
        <?= $this->render('_filter', ['model_filter' => $model_filter, 'type_requests' => $type_requests]); ?>
        
        <?= $this->render('grid', ['all_requests' => $all_requests]); ?>

    </div>
</div>

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