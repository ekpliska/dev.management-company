<?php

    use yii\helpers\Html;

/* 
 * Уведомление о новом опросе
 */
    
$post_path = $params['post_slug'] ? 
        Yii::$app->urlManager->createAbsoluteUrl(['/']) . "/news/{$params['post_slug']}" :
        Yii::$app->urlManager->createAbsoluteUrl(['/']) . "/voting/{$params['id_post']}";
?>

<table border="0" cellpadding="10" cellspacing="10" width="100%" align="center" valign="middle" style="font-family: sans-serif; font-size: 14px;">
    <thead>
        <tr>
            <th colspan="2" style="border-bottom: 1px solid #dddddd;">
                <img src="<?= Yii::$app->urlManager->createAbsoluteUrl(['images/main/elsa_logo.png']) ?>" alt="ELSA LOGO" width="170">
                <p style="font-family: sans-serif; font-size: 12px; letter-spacing: 2; color: #331fb6;">
                    ELECTRONIC SMART ASSISTEN
                </p>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2">
                <?= Yii::$app->formatter->asDate(time(), 'd MMMM, YYYY') ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Здравствуйте, вы подписанны на рассылку email-уведомлений на портале 
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/']) ?>">ELSA</a>. 
                Предлагаем вам принять участие в опросе на тему <?= "&#171;{$params['post_title']}&#187; " ?>. 
                С кратким содержанием опроса вы можете ознакомиться ниже в данном письме.
            </td>
        </tr>
        <tr>
            <td width="20%">
                <img src="<?= Yii::$app->urlManager->createAbsoluteUrl($params['post_image']) ?>" alt="Image publish" width="240" height="170" style="display:block;">
            </td>
            <td valign="top">
                <h3 style="color: #454545;"><?= Html::encode($params['post_title']) ?></h3>
                <br>
                <?= Html::encode($params['post_text']) ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center" height="70" style="padding: 20px;">
                <a href="<?= $post_path ?>" class="view-vote" target="_blank">
                    Принять участие
                </a>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 12px; color: #777777;" bgcolor="#dddddd">
                <b>Как отписаться от рассылки:</b>
                <ol>
                    <li>
                        Вы можете отписаться от рассылки в личном кабинете, раздел Профиль.
                    </li>
                    <li>
                        Или же перейдя по ссылке <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/']) ?>">Отписаться от рассылки</a>
                    </li>
                </ol>
            </td>
        </tr>
    </tbody>
</table>