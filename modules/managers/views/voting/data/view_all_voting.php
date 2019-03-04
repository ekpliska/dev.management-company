<?php

    use yii\helpers\Html;
    use yii\helpers\HtmlPurifier;
    use yii\widgets\LinkPager;
    use app\helpers\FormatHelpers;
    use app\models\RegistrationInVoting;
    use app\helpers\FormatFullNameUser;

/* 
 * Вид отрисовки списка всех созданных голосований
 */
?>
<?php if (isset($view_all_voting) && (count($view_all_voting) > 0)) : ?>
    <?php foreach ($view_all_voting as $voting) : ?>
    <div class="vote-card row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-md-12">
            <div class="vote-card__image">
                <?= Html::img("/web/{$voting['voting_image']}", ['alt' => $voting['voting_title']]) ?>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12 col-md-12">
            <div class="vote-card__content">
                <h1 class="vote-card__content_title">
                    <?= Html::a($voting['voting_title'] . " <span>ID{$voting['voting_id']}</span>", ['voting/view', 'voting' => $voting['voting_id']]) ?>
                    
                    <?php if (Yii::$app->user->can('VotingsEdit')) : ?>
                        <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'class' => 'pull-right btn-delete-question',
                                'data-toggle' => 'modal',
                                'data-target' => '#delete_voting_manager',
                                'data-voting' => $voting['voting_id']]) ?>
                    <?php endif; ?>
                    
                </h1>
                <div class="vote-card__status-info">
                    <div>
                            <span class="status-vote-<?= $voting['status'] ?>"><?= FormatHelpers::statusNameVoting($voting['status']) ?>
                    </div>
                    <div>
                        <span class="title">Начало голосования:</span>
                        <?= FormatHelpers::formatDate($voting['voting_date_start'], true, 1, false) ?>
                    </div>
                    <div>
                        <span class="title">Окончание голосования:</span>
                        <?= FormatHelpers::formatDate($voting['voting_date_end'], true, 1, false) ?>
                    </div>
                    <div>
                        <span class="title">Проголосовало</span>
                        <?php 
                            //  Определяем количество участников, которые закончили голосование 
                            $count = 0;
                            foreach ($voting['registration'] as $participant) :
                                if ($participant['finished'] == RegistrationInVoting::STATUS_FINISH_YES) :
                                    $count++;
                                endif; 
                            endforeach; 
                        ?>
                        <span class="span-count"><?= $count ?></span>
                    </div>
                    <div>
                        <span class="title">Автор:</span> <?= FormatFullNameUser::nameEmployeeByUserID($voting['voting_user_id']) ?>
                    </div>
                </div>
                <div class="vote-card__text">
                    <?= FormatHelpers::shortTextNews($voting['voting_text'], 70) ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?= LinkPager::widget([
            'pagination' => $pages
        ]);
    ?>
<?php else: ?>
<div class="notice info">
    <p>По указанному адресу функция голосования не создавалась.</p>
</div>
<?php endif; ?>