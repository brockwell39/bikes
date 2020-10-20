<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bicycle $bicycle
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $bicycle->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $bicycle->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Bicycles'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="bicycles form large-9 medium-8 columns content">
    <?= $this->Form->create($bicycle) ?>
    <fieldset>
        <legend><?= __('Edit Bicycle') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('title');
            echo $this->Form->control('type');
            echo $this->Form->control('gender');
            echo $this->Form->control('gears');
            echo $this->Form->control('photo');
            echo $this->Form->control('dir');
            echo $this->Form->control('weekday_price');
            echo $this->Form->control('weekend_price');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
