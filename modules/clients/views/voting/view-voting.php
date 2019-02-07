<?php

    use yii\helpers\Html;
    use yii\bootstrap\Modal;
    use yii\widgets\Breadcrumbs;
    use app\helpers\FormatHelpers;
    use app\modules\clients\widgets\ModalWindows;
    use app\models\Voting;
    use app\models\Answers;
    use app\modules\clients\widgets\AlertsShow;
    use app\models\RegistrationInVoting;
    use app\modules\clients\widgets\ResultsVote;

/* 
 * Просмотр отдельного голосования
 */
$this->title = Yii::$app->params['site-name'] . $voting['voting_title'];
$this->params['breadcrumbs'][] = ['label' => 'Опрос', 'url' => ['voting/index']];
$this->params['breadcrumbs'][] = $voting['voting_title'];
?>

<?= Breadcrumbs::widget([
        'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>

<?= AlertsShow::widget() ?>

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
            <?php 
                /* 
                 * "Принять участие" достпуно только в активных голосованиях, 
                 * Собственнику
                 */
                if (
                        ($voting['status'] == Voting::STATUS_ACTIVE || Yii::$app->user->can('clients')) 
                        && empty($is_register)
                    ) : 
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
        <div class=" col-md-3 voting-body_left">
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
                    <div class="col-lg-4 col-md-4 col-sm-2 col-xs-2 voting__participant_info text-center">
                        <?php $avatar = $participant['user_photo'] ? $participant['user_photo'] : "images/no-avatar.jpg" ?>
                        <?= Html::img("@web/{$avatar}", ['alt' => 'user-name', 'class' => 'img-responsive img-circle']) ?>
                        <?= Html::a($participant['clients_name'], ['view-profile', 'user_id' => $participant['user_id']], [
                                    'id' => 'view-profile',
                            ]) ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="col-md-9 voting-body_right">
            
            <?php if ($is_register['finished'] == RegistrationInVoting::STATUS_FINISH_YES) : // Результаты голсования выводим, если пользователь голосование завершил ?>
                <?= ResultsVote::widget(['voting_id' => $voting['voting_id']]) ?>
            <?php endif; ?>
            
            <?php /* Контент для участников голосования не подтвердивших свое участие */ ?>
            <?php if (empty($is_register) || $is_register['status'] == RegistrationInVoting::STATUS_DISABLED) : ?>
                <?php foreach ($voting['question'] as $key => $question) : ?>
                    <div class="questions-text">
                        <span><?= $question['questions_text'] ?></span>
                        <span class="span-count"><?= count($question['answer']) ?></span> Проголосовало
                    </div>
                <?php endforeach; ?>
            
            <?php elseif ($is_register['status'] == RegistrationInVoting::STATUS_ENABLED && $is_register['finished'] == RegistrationInVoting::STATUS_FINISH_NO) : ?>
                <?php /* Контент для зарегистрировавщихся участников */ ?>
            
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

<?php
    /* Модальное окно для загрузки профиля пользователя */
    Modal::begin([
        'id' => 'view-user-profile',
        'header' => 'Профиль пользователя',
        'closeButton' => false,
    ]);
?>
<?php Modal::end(); ?>