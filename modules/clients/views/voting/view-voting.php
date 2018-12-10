<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap\Modal;
    use yii\widgets\Breadcrumbs;
    use app\helpers\FormatHelpers;
    use app\models\RegistrationInVoting;
    use app\modules\clients\widgets\ModalWindows;
    use app\models\Voting;

/* 
 * Просмотр отдельного голосования
 */
$this->title = Yii::$app->params['site-name'] . $voting['voting_title'];
$this->params['breadcrumbs'][] = ['label' => 'Опрос', 'url' => ['voting/index']];
$this->params['breadcrumbs'][] = $voting['voting_title'];


$_start = strtotime($voting['voting_date_start']);
$_end = strtotime($voting['voting_date_end']);
$_now = time();
?>

<?= Breadcrumbs::widget([
        'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>

<div class="view-voting row">
    <div class="preview-voting">
        <?= Html::img('@web' . $voting['voting_image'], ['class' => 'voting-image', 'alt' => 'preview-vote']) ?>
        <div class="voting-info">
            <span><?= FormatHelpers::statusVotingInView($voting['status']) ?></span>
            <span><?= FormatHelpers::numberOfDaysToFinishVote($voting['voting_date_start'], $voting['voting_date_end']) ?></span>
        </div>
        <div class="voting-title-block">
            <h2 class="voting-title"><?= $voting['voting_title'] ?></h2>
            <p class="voting-description">
                <?= $voting['voting_text'] ?>
            </p>
            <?php if (Yii::$app->user->can('clients')) : ?>
                <?= Html::button('Принять участие', [
                        'class' => 'register-in-voting',
                        'id' => 'get-voting-in',
                        'data-voting' => $voting['voting_id'],
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="col-md-12 voting-body">
        <div class=" col-md-3 voting-body_left">
            <p>
                <span class="span-count">#TODO</span> Проголосовало
            </p>
            #TODO
            <br />
            #TODO
            <br />
            #TODO
            <br />
            #TODO
            <br />
        </div>
        
        <div class="col-md-9 voting-body_right">
            <?php foreach ($voting['question'] as $key => $question) : ?>
                <div class="questions-text">
                    <span><?= $question['questions_text'] ?></span>
                    <span class="span-count">#TODO</span> Проголосовало
                </div>
            <?php endforeach; ?>
            
            <?php /* Контент для зарегистрировавщихся участников
            <div class="questions-text-show">
                <h4>
                    <i class="glyphicon glyphicon-ok"></i> #QUESTIONS
                </h4>
                <div class="btn-group">
                    <p>1</p>
                    <button type="button" class="btn btn-primary">Apple</button>
                    <p>2</p>
                    <button type="button" class="btn btn-primary">Samsung</button>
                    <p>3</p>
                    <button type="button" class="btn btn-primary">Sony</button>
                </div>
            </div>
            */ ?>
            
        </div>
        
    </div>
    
</div>

<?php if ($is_register['status'] == RegistrationInVoting::STATUS_DISABLED && Yii::$app->user->can('clients')) : ?>

    <?= $this->render('modal/participate-in-voting', [
            'model' => $model,
            'voting_id' => $voting['voting_id']]) ?>

<?php endif; ?>


<?php 
    /* Если кука отправки СМС кода существует сразу же загружаем модальное окно на ввод кода */
    if ($modal_show == true) {
    $this->registerJs("$('#participate-in-voting-" . $voting['voting_id'] . "').modal('show');");    
} 
?>

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
            modalMessage.find('.vote-message_span').text('Регистрация на голосование начнется ' + _dateStart);
            modalMessage.modal('show');
            return false;
        } else if (dateNow > dateEnd) {
            modalMessage.find('.vote-message_span').text('Голосование завершилось');
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
                    $(this).attr('disabled', true);
                    $('#participate-in-voting-" . $voting['voting_id'] . "').modal('show');
                } else if (response.success === false) {
                    console.log(response);
                }
            });
        }
    });
");
?>

<?= ModalWindows::widget(['modal_view' => 'participate_modal']) ?>