<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice $invoice
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Invoices'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Bookings'), ['controller' => 'Bookings', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Booking'), ['controller' => 'Bookings', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="invoices form large-9 medium-8 columns content">
    <?= $this->Form->create($invoice) ?>
    <fieldset>
        <legend><?= __('Add Invoice') ?></legend>
        <?php
            echo $this->Form->control('booking_id', ['options' => $bookings]);
            echo $this->Form->control('status');
            echo $this->Form->control('weekday_amount');
            echo $this->Form->control('weekday_quantity');
            echo $this->Form->control('weekend_amount');
            echo $this->Form->control('weekend_quantity');
            echo $this->Form->control('deposit');
            echo $this->Form->control('deposit_status');
            echo $this->Form->control('disputed_amount');
            echo $this->Form->control('dispute_status');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
