<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\models\Voting;
    use app\helpers\FormatHelpers;

$this->title ="Голосование"
?>

<div class="vote-lists row">
    <?php if (isset($voting_list) && count($voting_list) > 0) : ?>
        <?php foreach ($voting_list as $key => $voting) : ?>

        <div class="col-md-4">
            <div class="vote-card-preview">
                <?= Html::img('@web' . $voting['voting_image'], ['class' => 'vote-card-img']) ?>
                <span class="vote-card_date-end">
                    <?= FormatHelpers::numberOfDaysToFinishVote($voting['voting_date_start'], $voting['voting_date_end']) ?>
                </span>
                <div class="vote-card_title">
                    <a href="<?= Url::to(['voting/view-voting', 'voting_id' => $voting['voting_id']]) ?>">
                        <?= FormatHelpers::shortTitleOrText($voting['voting_title'], 25) ?>
                    </a>
                    <i class="glyphicon glyphicon-<?= $voting['status'] == Voting::STATUS_CLOSED ? 'flag' : 'ok' ?>"></i>
                </div>
                <div class="vote-card_text">
                    <?= FormatHelpers::shortTitleOrText($voting['voting_text'], 250) ?>
                </div>
                <div class="vote-card_count">
                    <div class="col-md-2">Проголосовало</div>
                    <div class="col-md-10 text-right"><span class="vote-bange-count">#TODO</span></div>
                </div>
                <div class="vote-card_participants">
                    <div class="col-md-2">Участвуют</div>
                    <div class="col-md-10 text-right"><span class="vote-bange-count">#TODO</span></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>