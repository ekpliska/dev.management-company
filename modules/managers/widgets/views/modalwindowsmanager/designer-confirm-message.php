<?php
    
    use yii\bootstrap\Modal;
    use yii\helpers\Html;
    

/* 
 * Модальное окно для блока "Конструктор заявок"
 */
?>


<?php
Modal::begin([
    'id' => 'designer-confirm-message',
    'header' => 'Внимание!',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
    'clientOptions' => [
        'backdrop' => 'static', 
        'keyboard' => false,
    ],
]);
?>

    <p class="modal-confirm"></p>
    
    <div class="modal-footer">
        <?= Html::button('Удалить', ['class' => 'btn btn red-outline-btn bt-bottom2 delete_record__des', 'data-dismiss' => 'modal']) ?>
        <?= Html::button('Отмена', ['class' => 'btn btn blue-outline-btn white-btn', 'data-dismiss' => 'modal']) ?>
    </div>
    
<?php
Modal::end(); 
?>
