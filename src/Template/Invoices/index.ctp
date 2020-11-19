<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice[]|\Cake\Collection\CollectionInterface $invoices
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Invoice'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Bookings'), ['controller' => 'Bookings', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Booking'), ['controller' => 'Bookings', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="invoices index large-9 medium-8 columns content">
    <h3><?= __('Invoices') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('booking_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('weekday_amount') ?></th>
                <th scope="col"><?= $this->Paginator->sort('weekday_quantity') ?></th>
                <th scope="col"><?= $this->Paginator->sort('weekend_amount') ?></th>
                <th scope="col"><?= $this->Paginator->sort('weekend_quantity') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deposit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deposit_status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('disputed_amount') ?></th>
                <th scope="col"><?= $this->Paginator->sort('dispute_status') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $invoice): ?>
            <tr>
                <td><?= $this->Number->format($invoice->id) ?></td>
                <td><?= $invoice->has('booking') ? $this->Html->link($invoice->booking->id, ['controller' => 'Bookings', 'action' => 'view', $invoice->booking->id]) : '' ?></td>
                <td><?= h($invoice->status) ?></td>
                <td><?= $this->Number->format($invoice->weekday_amount) ?></td>
                <td><?= h($invoice->weekday_quantity) ?></td>
                <td><?= $this->Number->format($invoice->weekend_amount) ?></td>
                <td><?= $this->Number->format($invoice->weekend_quantity) ?></td>
                <td><?= $this->Number->format($invoice->deposit) ?></td>
                <td><?= h($invoice->deposit_status) ?></td>
                <td><?= $this->Number->format($invoice->disputed_amount) ?></td>
                <td><?= h($invoice->dispute_status) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $invoice->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $invoice->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $invoice->id], ['confirm' => __('Are you sure you want to delete # {0}?', $invoice->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
