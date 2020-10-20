<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bicycle $bicycle
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Bicycles'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="bicycles form large-9 medium-8 columns content">
        <legend><?= __('Add Bicycle') ?></legend>
        <?php
        echo $this->Form->create($bicycle, ['type' => 'file']);
            echo $this->Form->control('title');
            $bike_type = ['MTB'=>'MTB','Road'=>'Road'];
            echo $this->Form->control('type', ['type' => 'select', 'options' => $bike_type, 'label' => __('Bike Type')]);
            $genders = ['Men\'s'=>'Men\'s','Women\'s'=>'Women\'s'];
            echo $this->Form->control('gender', ['type' => 'select', 'options' => $genders, 'label' => __('Men\'s or Women\'s')]);
            echo $this->Form->control('gears',['label' => 'Number of Gears']);
            echo $this->Form->control('photo', ['type' => 'file']);
            echo $this->Form->control('weekday_price',['label' => 'Price per weekday in £']);
            echo $this->Form->control('weekend_price',['label' => 'Price per weekend day in £']);
            echo $this->Form->button(__('Submit'));
            echo $this->Form->end(); ?>
</div>
