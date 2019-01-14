<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php // echo '<pre>'; var_dump($results); ?>
<?php if (!empty($results) && count($results) > 0) : ?>
<div class="questions-text-show-form">
<?php foreach ($results as $result) : ?>

    <div class="questions-text-show">
        <h4>
            <i class="glyphicon glyphicon-ok marker-vote-10"></i>
            <?= $result['text_question'] ?>
        </h4>
        <div class="btn-block text-center">
            <div class="btn-group btn-group-lg" role="group" aria-label="Button block" id="btn-group-<?= $key ?>">
            </div>
        </div>
    </div>

<?php endforeach; ?>
    </div>
<?php endif; ?>