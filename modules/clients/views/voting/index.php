<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Breadcrumbs;
    use app\models\Voting;
    use app\helpers\FormatHelpers;

$this->title = Yii::$app->params['site-name'] . 'Опрос';
$this->params['breadcrumbs'][] = 'Опрос';

//foreach ($voting_list as $key => $voting) {
//    echo '<pre>'; var_dump($voting['registration']);
//}
//die();
?>

<?= Breadcrumbs::widget([
        'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>  

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
                    <p class="date-start-vote"><?= FormatHelpers::formatDate($voting['voting_date_start'], false, 0, false) ?></p>
                </div>
                <div class="vote-card_text">
                    <?= FormatHelpers::shortTitleOrText($voting['voting_text'], 250) ?>
                </div>
                <div class="vote-card_count">
                    <?php 
                        // Количество учатников закончивших голосование
                        $count_finished = 0;
                        // Количество зарегистрированных участников
                        $count_participants = 0;
                        
                        foreach ($voting['registration'] as $value) {
                            if ($value['finished'] == app\models\RegistrationInVoting::STATUS_FINISH_YES) 
                                $count_finished++;
                            if ($value['status'] == app\models\RegistrationInVoting::STATUS_ENABLED)
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
    <?php endif; ?>
</div>