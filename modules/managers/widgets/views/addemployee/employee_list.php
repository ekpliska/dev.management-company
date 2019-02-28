<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

/* 
 * Отрисовка модального окна "Назначить диспетчера"
 */

?>

<?php
    Modal::begin([
        'id' => 'add-dispatcher-modal',
        'header' => 'Назначить диспетчера',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],        
    ]);
?>

    <?= Html::input('text', 'search-employee', null, ['id' => 'search-employee', 'class' => 'search-employee_input', 'placeHolder' => 'Поиск...']) ?>

    <div class="error-message" style="color: #ef693a; font-size: 12px;"></div>
    
    <div class="list-group employee-lists" id="dispatcherList">
        
        <ul id="employees-list">
        <?php if (isset($dispatcher_list)) : ?>
            <?php foreach ($dispatcher_list as $dispatcher) : ?>
                <li>
                    <a class="" data-employee="<?= $dispatcher['id'] ?>">
                        <?= $dispatcher['surname'] ?> <?= $dispatcher['name'] ?> <?= $dispatcher['second_name'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
            <div class="notice info">
                <p>Сотрудники с должностью Диспетчер в системе на найдены</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="modal-footer">
        <?= Html::button('Назначить', [
                'class' => 'btn-modal btn-modal-yes add_dispatcher__btn',
                'type-request' => $type,
                'data-dismiss' => 'modal']) ?>
        <?= Html::button('Закрыть', [
                'class' => 'btn-modal btn-modal-no', 
                'data-dismiss' => 'modal']) ?>
    </div>

<?php Modal::end(); ?>

<?php
    Modal::begin([
        'id' => 'add-specialist-modal',
        'header' => 'Назначить специалиста',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],        
    ]);
?>

    <?= Html::input('text', 'search-employee', null, ['id' => 'search-employee', 'class' => 'search-employee_input', 'placeHolder' => 'Поиск...']) ?>
    
    <div class="error-message" style="color: #ef693a; font-size: 12px;"></div>
    
    <div class="list-group employee-lists" id="specialistList">
        
        <ul id="employees-list">
        <?php if (isset($specialist_list) && !empty($specialist_list)) : ?>
            <?php foreach ($specialist_list as $specialist) : ?>
                <li>
                    <a class="" data-employee="<?= $specialist['id'] ?>">
                        <?= $specialist['surname'] ?> <?= $specialist['name'] ?> <?= $specialist['second_name'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php else : ?>
            <div class="notice info">
                <p>Специалисты на найдены</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="modal-footer">
        <?= Html::button('Назначить', [
                'class' => 'btn-modal btn-modal-yes add_specialist__btn',
                'type-request' => $type,
                'data-dismiss' => 'modal']) ?>
        <?= Html::button('Закрыть', [
                'class' => 'btn-modal btn-modal-no', 
                'data-dismiss' => 'modal']) ?>
    </div>

<?php Modal::end(); ?>    


