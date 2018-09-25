<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Завяки, главная
 */
$this->title = 'Заявки';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::button('Заявка (+)', [
        'class' => 'btn btn-success btn-sm',
        'data-target' => '#create-new-requests',
        'data-toggle' => 'modal']) ?>
    
    <hr />
    
<?php
echo '<pre>';
var_dump($model->findClientPhone('+7 (920) 333-77-91'));
?>
    
</div>
<?= $this->render('form/create', [
        'model' => $model,
        'service_categories' => $service_categories,
        'service_name' => $service_name,
        'flat' => $flat,
    ]) ?>

<?php

$phone = '+7 (111) 111-11-11';
//$phone = substr('7 (111) 111-11-10', 8);
//$phone = str_replace('-', '', $phone);


$client = \app\models\Clients::find()
        ->where(['clients_mobile' => $phone])
        ->orWhere(['clients_phone' => $phone])
//        ->where(['=', new \yii\db\Expression('REPLACE(RIGHT(clients_mobile, 9), "-", "")'), $phone])
//        ->where(['=', new \yii\db\Expression('REPLACE(RIGHT(clients_phone, 9), "-", "")'), $phone])
        ->asArray()
        ->one();

echo '<pre>';
var_dump($client);;

?>