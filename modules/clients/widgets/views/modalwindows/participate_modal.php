<?php
    use yii\bootstrap\Modal;
    use yii\helpers\Html;
/* 
 * Модальные окна для процедуры "Принять участние в голосовании"
 */
?>

<?php
Modal::begin([
    'id' => 'participate_modal-message',
    'header' => '',
    'closeButton' => [
        'class' => 'close close modal-close-btn',
    ],
]);
?>

    <div class="text-center">
        <span class="vote-message_span"></span>        
    </div>
    <h4 class="modal-title modal-title-vote"></h4>

    <div class="modal-footer">
        <?= Html::button('Закрыть', [
                'data-dismiss' => 'modal', 
                'class' => 'btn-modal btn-modal-no',
        ]) ?>
    </div>
<?php Modal::end(); ?> 