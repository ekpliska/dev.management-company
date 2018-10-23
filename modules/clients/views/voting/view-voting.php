<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap\Modal;
    use app\helpers\FormatHelpers;
    use app\modules\clients\widgets\ModalWindows;

/* 
 * Просмотр отдельного голосования
 */
$_start = strtotime($voting['voting_date_start']);
$_end = strtotime($voting['voting_date_end']);
$_now = time();
$btn_disabled = ($_start > time() || $_end < time()) ? true : false;

$this->title = $voting['voting_title'];
?>
<div class="clients-default-index">
    
    <h1><?= $this->title ?></h1>
    
    <div class="col-md-3">
        <?= Html::img('@web' . $voting['voting_image'], ['alt' => $voting['voting_title'], 'style' => 'width: 100%;']) ?>
        <hr />
        Дата начала голосования: <?= FormatHelpers::formatDate($voting['voting_date_start'], false) ?>
        <br />
        Дата завершения голосования: <?= FormatHelpers::formatDate($voting['voting_date_end'], false) ?>
        <br />
        <?= FormatHelpers::numberOfDaysToFinishVote($voting['voting_date_start'], $voting['voting_date_end']) ?>
        <hr />
        Статус: <?= FormatHelpers::statusNameVoting($voting['status']) ?>
        <hr />
        <?= Html::button('Принять участие', [
                'class' => 'btn btn-primary',
                'id' => 'get-voting-in',
                'data-voting' => $voting['voting_id'],
//                'disabled' => $btn_disabled,
            ]) ?>
    </div>
    <div class="col-md-9">
        <?= $voting['voting_text'] ?>
        <hr />
        <table width="100%">
            <?php foreach ($voting['question'] as $key => $question) : ?>
                <tr>
                    <td colspan="3">
                        <?= $question['questions_text'] ?>
                    </td>
                </tr>
                <tr>
                    <td><?= 'YES' ?></td>
                    <td><?= 'NO' ?></td>
                    <td><?= 'HIT' ?></td>
                </tr>        
            <?php endforeach; ?>
        </table>            
    </div>
    
</div>

<?php
$this->registerJs("
    function checkDate(){
        var dateNow = " . $_now . ";
        var dateStart = " . $_start . ";
        var _dateStart = '" . FormatHelpers::formatDate($voting['voting_date_start'], true, 1) . "';
        var dateEnd = " . $_end . ";
        var titleModal = '" . $voting['voting_title'] . "';
        var modalMessage = $('#participate_modal-message');
        modalMessage.find('.modal-title').text(titleModal);
        
        if (dateNow < dateStart) {
            modalMessage.find('.modal__text').text('Регистрация на голосование начнется ' + _dateStart);
            modalMessage.modal('show');
            return false;
        } else if (dateNow > dateEnd) {
            modalMessage.find('.modal__text').text('Голосование завершилось');
            modalMessage.modal('show');
            return false;
        }
        
        return true;
    }
    
    $('#get-voting-in').on('click', function(){
        var voting = $(this).data('voting');
        if (checkDate() === true) {
            $.ajax({
              type: 'POST',
              url: 'participate-in-voting',
              data: {voting: voting}
            }).done(function(response) {
                if (response.success === true) {
                    console.log(response.voting_id);
                } else if (response.success === false) {
                    console.log('ошибка');
                }
            });
        }
    });
");
?>

<?= ModalWindows::widget(['modal_view' => 'participate_modal']) ?>