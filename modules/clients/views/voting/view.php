<?php

    use yii\helpers\Html;
    use yii\bootstrap\Modal;
    use app\helpers\FormatHelpers;
    use app\models\Voting;
    use app\models\Answers;
    use app\models\RegistrationInVoting;
    use app\modules\clients\widgets\ResultsVote;
    use app\modules\clients\widgets\ChatVote;

/* 
 * Просмотр отдельного голосования
 */
$this->title = Yii::$app->params['site-name'] . $voting['voting_title'];
$_answer = '';
?>

<div class="view-voting row">
    <div class="preview-voting">
        <?= Html::img(Yii::getAlias('@web') . $voting['voting_image'], ['class' => 'voting-image', 'alt' => 'preview-vote']) ?>
        <div class="voting-info">
            <span><?= FormatHelpers::statusVotingInView($voting['status']) ?></span>
            <span><?= FormatHelpers::numberOfDaysToFinishVote($voting['voting_date_start'], $voting['voting_date_end']) ?></span>
        </div>
        <div class="voting-title-block">
            <h2 class="voting-title">
                <?= Html::encode($voting['voting_title']) ?>
            </h2>
            <p class="voting-description">
                <?= FormatHelpers::shortTitleOrText($voting['voting_text'], 255) ?>
            </p>
            <?php 
                /* 
                 * "Принять участие" доступно только в активных голосованиях, 
                 * Собственнику
                 */
                if ($voting['status'] == Voting::STATUS_ACTIVE && empty($is_register)) : 
            ?>
                <?= Html::button('Принять участие', [
                        'class' => 'register-in-voting',
                        'id' => 'get-voting-in',
                        'data-voting' => $voting['voting_id'],
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="col-md-12 voting-body">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 voting-body_left">
            <p>
                <span class="span-count"><?= count($participants) ?></span> Проголосовало
            </p>
            <?php
                /*
                 * Блок формирования участников, завершивших голосование
                 */
                if (isset($participants) && count($participants) > 0) : 
            ?>
                <?php foreach ($participants as $participant) : ?>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 voting__participant_info text-center">
                        <?php $avatar = $participant['user_photo'] ? $participant['user_photo'] : "/images/no-avatar.jpg" ?>
                        <?= Html::img(Yii::getAlias('@web') . $avatar, ['alt' => 'user-name', 'class' => 'img-circle user-finish-vote']) ?>
                        <?= Html::a($participant['clients_name'], ['view-profile', 'user_id' => $participant['user_id']], [
                                    'id' => 'view-profile',
                            ]) ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 voting-body_right">
            
            <?php /* Результаты голсования выводим, если пользователь голосование завершил или опрос закрыт */ ?>
            <?php if ($is_register['finished'] == RegistrationInVoting::STATUS_FINISH_YES || $voting['status'] == Voting::STATUS_CLOSED) : ?>
                <?= ResultsVote::widget(['voting_id' => $voting['voting_id']]) ?>
            <?php endif; ?>
            
            <?php /* Контент для участников голосования не подтвердивших свое участие */ ?>
            <?php if ((empty($is_register) || $is_register['status'] == RegistrationInVoting::STATUS_DISABLED) && $voting['status'] == Voting::STATUS_ACTIVE) : ?>
                <?php foreach ($voting['question'] as $key => $question) : ?>
                    <div class="questions-text">
                        <span><?= $question['questions_text'] ?></span>
                        <span class="span-count"><?= count($question['answer']) ?></span> Проголосовало
                    </div>
                <?php endforeach; ?>
            
            <?php /* Контент для зарегистрировавщихся участников */ ?>
            <?php elseif ($is_register['status'] == RegistrationInVoting::STATUS_ENABLED && $is_register['finished'] == RegistrationInVoting::STATUS_FINISH_NO) : ?>
                <div class="questions-text-show-form">
                    <?php $count_answer = 0; ?>
                    <?php foreach ($voting['question'] as $key => $question) : ?>
                    <div class="questions-text-show">
                        <h4>
                            <?php 
                                /* В цикле находим ответ текущего пользователя по конктерному вопросу */
                             foreach ($question['answer'] as $answer) {
                                 if ($answer['answers_user_id'] == Yii::$app->user->identity->id && $answer['answers_questions_id'] == $question['questions_id']) {
                                     $_answer = $answer['answers_vote'];
                                 }
                             }
                            ?>
                            <i class="glyphicon glyphicon-ok marker-vote-<?= $question['questions_id'] ?> <?= !isset($_answer) ? 'show-marker' : '' ?>"></i>
                            <?= $question['questions_text'] ?>
                        </h4>
                        <?php if ($is_register['finished'] == RegistrationInVoting::STATUS_FINISH_NO) : ?>
                            <div class="btn-block text-center">
                                <div class="btn-group btn-group-lg" role="group" aria-label="Button block" id="btn-group-<?= $key ?>">
                                    <?php foreach (Answers::getAnswersArray() as $type_answer => $answer) : ?>
                                        <?php $class = ($_answer == $type_answer) ? 'btn-set-voting-active' : ''; ?>
                                        <?= Html::button($answer, [
                                                'class' => "btn btn-primary btn-set-voting {$class}",
                                                'id' => "btn-vote-yes-{$key}",
                                                'data-question' => $question['questions_id'],
                                                'data-type-answer' => $type_answer,
                                        ]) ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($is_register['finished'] == RegistrationInVoting::STATUS_FINISH_NO) : ?>
                    <div class="finished-voting text-center">
                        <?= Html::button('Проголосовать', [
                                'id' => 'finished-voting-btn',
                                'class' => 'btn blue-btn',
                                'data-voting' => $voting['voting_id'],
                                'data-question' => count($voting['question']),
                        ]) ?>
                    </div>
                <?php endif; ?>
            
            <?php endif; ?>
            
        </div>
        
    </div>
    
    <?php if ($is_register['status'] == RegistrationInVoting::STATUS_ENABLED) : ?>
        <?= ChatVote::widget(['vote_id' => $voting['voting_id']]) ?>
    <?php endif; ?>
    
</div>

<?php if (Yii::$app->user->can('clients')) : ?>

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
// Дата начала голосования
$_start = strtotime($voting['voting_date_start']);
// Дата завершения голосования
$_end = strtotime($voting['voting_date_end']);
// Декущая дата
$_now = time();

$this->registerJs("
    
    function checkDate(){
        var dateNow = " . $_now . ";
        var dateStart = " . $_start . ";
        var _dateStart = '" . FormatHelpers::formatDate($voting['voting_date_start'], true, 1) . "';
        var dateEnd = " . $_end . ";
        var titleModal = '" . Html::encode($voting['voting_title']) . "';
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
                    // console.log(response.voting_id);
                    $(this).attr('disabled', true);
                    $('#participate-in-voting-" . $voting['voting_id'] . "').modal('show');
                } else if (response.success === false) {
                    // console.log(response);
                }
            });
        }
    });
");
?>

<?php
    /* Модальное окно для загрузки профиля пользователя */
    Modal::begin([
        'id' => 'view-user-profile',
        'header' => 'Профиль участника',
        'size' => Modal::SIZE_SMALL,
        'closeButton' => false,
    ]);
?>
<?php Modal::end(); ?>