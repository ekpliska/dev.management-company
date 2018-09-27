<?php

    use yii\helpers\Html;

/* 
 * Отрисовка модального окна "Назначить диспетчера"
 */

?>

<div id="add-dispatcher-modal" class="modal fade" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Назначить диспетчера
                </h4>
            </div>
            <div class="modal-body">
                
                <div class="error-message" style="color: red;"></div>
                
                <div class="list-group" id="myList" role="tablist">
                    <?php foreach ($dispatcher_list as $dispatcher) : ?>
                        <a class="list-group-item list-group-item-action" data-dispatcher="<?= $dispatcher['id'] ?>">
                            <?= $dispatcher['surname'] ?> <?= $dispatcher['name'] ?> <?= $dispatcher['second_name'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::button('Назначить', ['class' => 'btn btn-primary add_dispatcher__btn', 'data-dismiss' => 'modal']) ?>
                <?= Html::button('Закрыть', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
            </div>
        </div>
    </div>
</div>

<?php 
$this->registerJs("

    $('#add-dispatcher-modal').on('show.bs.modal', function (e) {
        var requestId = $('.switch-request').data('request');
        var dispatcherId = $('.btn-dispatcher').data('dispatcher');
        $('.error-message').text('');
        $('.add_dispatcher__btn').data('request', requestId);
        $('a[data-dispatcher=' + dispatcherId + ']').addClass('active');
    });

    $('#myList a').on('click', function() {
        var dispatcherId = $(this).data('dispatcher');
        $('#myList').find('.active').removeClass('active');
        $(this).toggleClass('active');
        $('.add_dispatcher__btn').data('dispatcher', dispatcherId);
        console.log(dispatcherId);
    });

    $('.add_dispatcher__btn').on('click', function(e) {
        e.preventDefault();
        
        var dispatcherId = $(this).data('dispatcher');
        var requestId = $(this).data('request');

        // Проверяем налицие дата параметров
        if (dispatcherId === undefined || requestId === undefined) {
            $('.error-message').text('Прежде чем назначить диспетчера, выберите его из списка');
            return false;
        } else {
            $.ajax({
                url: 'choose-dispatcher',
                method: 'POST',
                data: {
                    dispatcherId: dispatcherId,
                    requestId: requestId,
                },
                success: function(response) {
                    console.log(response.data);
                },
                error: function() {
                    console.log('error');
                },
            });
        }
    });
");
?>
