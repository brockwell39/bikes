<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice $invoice
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Invoice'), ['action' => 'edit', $invoice->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Invoice'), ['action' => 'delete', $invoice->id], ['confirm' => __('Are you sure you want to delete # {0}?', $invoice->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Invoices'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Invoice'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Bookings'), ['controller' => 'Bookings', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Booking'), ['controller' => 'Bookings', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="invoices view large-9 medium-8 columns content">
    <h3><?= h($invoice->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Booking') ?></th>
            <td><?= $invoice->has('booking') ? $this->Html->link($invoice->booking->id, ['controller' => 'Bookings', 'action' => 'view', $invoice->booking->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= h($invoice->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Weekday Quantity') ?></th>
            <td><?= h($invoice->weekday_quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deposit Status') ?></th>
            <td><?= h($invoice->deposit_status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Dispute Status') ?></th>
            <td><?= h($invoice->dispute_status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($invoice->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Weekday Amount') ?></th>
            <td><?= $this->Number->format($invoice->weekday_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Weekend Amount') ?></th>
            <td><?= $this->Number->format($invoice->weekend_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Weekend Quantity') ?></th>
            <td><?= $this->Number->format($invoice->weekend_quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deposit') ?></th>
            <td><?= $this->Number->format($invoice->deposit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Disputed Amount') ?></th>
            <td><?= $this->Number->format($invoice->disputed_amount) ?></td>
        </tr>
    </table>
</div>
