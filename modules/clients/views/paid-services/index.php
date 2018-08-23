<?php
    
/* 
 * Заявки (Обзая страница)
 */
$this->title = 'История услуг';
?>

<div class="paid-services-default-index">
    <h1><?= $this->title ?></h1>
    
    <div class="grid-view">
        <?= $this->render('grid/grid', ['all_orders' => $all_orders]) ?>
    </div>
    
</div>

<?php
/* Фильтр заявок пользователя по 
 * ID лицевого счета, типу и статусу заявки
 */
$this->registerJs("    
    $('.current__account_list').on('change', function(e) {
        e.preventDefault();
        var account_id = $('.current__account_list').val();
        
        $.ajax({
            url: 'filter-by-account?account_id=' + account_id,
            method: 'POST',
            data: {
                account_id: account_id,
            },
            success: function(response){
                if (response.status === false) {
                    $('.grid-view').html('Возникла ошибка при передаче данных. Обновите страницу нажав на клавиатуре клавишу F5');
                } else {
                    $('.grid-view').html(response.data);
                }
            }
        });
    });
");
?>