<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

$this->title ="Голосование"
?>

<div class="clients-default-index">
    
    <h1><?= $this->title ?></h1>
    
    <?php if (isset($voting_list) && count($voting_list) > 0) : ?>
        <?php foreach ($voting_list as $key => $voting) : ?>

            <div class="col-md-4">
                <a href="<?= Url::to(['voting/view-voting', 'voting_id' => $voting['voting_id']]) ?>">
                    <?= Html::img('@web' . $voting['voting_image'], ['alt' => $voting['voting_title'], 'style' => 'width:100%']) ?>
                </a>
                
                <h5>
                    <?= FormatHelpers::statusNameVoting($voting['status']) ?>
                </h5>
                
                <h4>
                    <?= Html::a($voting['voting_title'], ['view-voting', 'voting_id' => $voting['voting_id']]) ?>
                </h4>
                
                <?= FormatHelpers::formatDate($voting['voting_date_start'], false) ?>
                <br />
                <?= FormatHelpers::numberOfDaysToFinishVote($voting['voting_date_start'], $voting['voting_date_end']) ?>
                <br />
                <p><?= FormatHelpers::shortTextNews($voting['voting_text']) ?></p>
                
                <?= Html::a('Голосовать', ['voting/view-voting', 'voting_id' => $voting['voting_id']]) ?>
                    
            </div>

            <?php if (($key + 1) % 3 == 0) : ?>
                <div class="clearfix"></div>
            <?php endif; ?>

        <?php endforeach; ?>
    <?php endif; ?>
        
</div>