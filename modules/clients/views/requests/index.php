<?php
    
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Breadcrumbs;
    
/* 
 * Заявки (Общая страница)
 */
$this->title = Yii::$app->params['site-name'] . 'Заявки';
$this->params['breadcrumbs'][] = 'Заявки';
?>

<div class="requests-page">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>    
    
    <?= $this->render('data/grid', ['all_requests' => $all_requests]); ?>
    <?= Html::button('', ['class' => 'create-request-btn btn-link pull-right', 'data-toggle' => 'modal', 'data-target' => '#add-request-modal']) ?>
</div>

<?= $this->render('form/add-request', ['model' => $model, 'type_requests' => $type_requests]) ?>

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