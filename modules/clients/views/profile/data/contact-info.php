<?php
    
    use yii\helpers\Html;
    
/* 
 * Блок "Контактная информация"
 * Профиль
 */

?>

<div class="col-md-4" style="padding: 0;">
    <p>Фамилия</p>
    <?= Html::input('text', 'surname', $info['surname'], [
        'class' => 'form-control', 
        'disabled' => true]) 
    ?>
</div>
<div class="col-md-4" style="padding: 0;">
    <p>Имя</p>
    <?= Html::input('text', 'name', $info['name'], [
        'class' => 'form-control', 
        'disabled' => true]) 
    ?>
</div>
<div class="col-md-4" style="padding: 0;">
    <p>Отчество</p>
    <?= Html::input('text', 'second_name', $info['second_name'], [
        'class' => 'form-control', 
        'disabled' => true]) 
    ?>
</div>
                        
<p>Домашний телефон</p>
<div class="col-md-12" style="padding: 0;">
    <?= Html::input('text', 'phone', $info['phone'], [
        'class' => 'form-control', 
        'disabled' => true]) 
    ?>
</div>
                        
<p>Мобильный телефон</p>
<div class="col-md-12" style="padding: 0;">
    <?= Html::input('text', 'mobile', $info['mobile'], [
        'class' => 'form-control', 
        'disabled' => true]) 
    ?>
</div>
                        
<p>Электронная почта</p>
    <div class="col-md-12" style="padding: 0;">
    <?= Html::input('text', 'email', $info['email'], [
        'class' => 'form-control', 
        'disabled' => true])
    ?>
</div>
                        
<p>Номер лицевого счета</p>
    <div class="col-md-12" style="padding: 0;">
    <?= Html::input('text', 'account-number',
            $info['account'], [
                'class' => 'form-control', 
                'disabled' => true, 
                'id' => 'account-number'
        ])
    ?>
</div>