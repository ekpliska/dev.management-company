<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\models\Voting;
    use app\helpers\FormatHelpers;
    use app\models\RegistrationInVoting;

/*
 * Голосование, главная страница
 */    
    
$this->title = Yii::$app->params['site-name'] . 'Опрос';
?>

<div class="vote-lists row">
    <?php if (isset($voting_list) && count($voting_list) > 0) : ?>
        <?php foreach ($voting_list as $key => $voting) : ?>

        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-6">
            <div class="vote-card-preview">
                <div class="vote-card-preview__image">
                    <?= Html::img(Yii::getAlias('@web') . $voting->voting_image, ['class' => 'vote-card-img']) ?>
                    <div class="vote-card-preview__overlay">
                        <div class="overlay__text">
                            <?= Html::a('Принять участие', ['voting/view', 'voting_id' => $voting['voting_id']], ['class' => '']) ?>
                        </div>
                    </div>
                    <span class="vote-card_date-end">
                        <?= FormatHelpers::numberOfDaysToFinishVote($voting->voting_date_start, $voting->voting_date_end) ?>
                    </span>
                </div>
                <div class="vote-card_title">
                    <a href="<?= Url::to(['voting/view', 'voting_id' => $voting->voting_id]) ?>">
                        <?= FormatHelpers::shortTitleOrText($voting->voting_title, 25) ?>
                    </a>
                    <i class="glyphicon glyphicon-<?= $voting->status == Voting::STATUS_ACTIVE ? 'ok' : 'flag' ?>"></i>
                    <p class="date-start-vote"><?= FormatHelpers::formatDate($voting->voting_date_start, false, 0, false) ?></p>
                </div>
                <div class="vote-card_text">
                    <?= FormatHelpers::shortTitleOrText($voting->voting_text, 200) ?>
                </div>
                <div class="vote-card_count">
                    <?php 
                        // Количество учатников закончивших голосование
                        $count_finished = 0;
                        // Количество зарегистрированных участников
                        $count_participants = 0;
                        
                        foreach ($voting->registration as $value) {
                            if ($value->finished == RegistrationInVoting::STATUS_FINISH_YES) 
                                $count_finished++;
                            if ($value->status == RegistrationInVoting::STATUS_ENABLED)
                                $count_participants++;
                        }
                    ?>
                    <div class="col-md-2">Проголосовало</div>
                    <div class="col-md-10 text-right">
                        <span class="vote-bange-count">
                            <?= $count_finished ?>
                        </span>
                    </div>
                </div>
                <div class="vote-card_participants">
                    <div class="col-md-2">Участвуют</div>
                    <div class="col-md-10 text-right">
                        <span class="vote-bange-count">
                            <?= $count_participants ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="notice info">
            <p>Голосования еще не проводились</p>
        </div>
    <?php endif; ?>
</div>