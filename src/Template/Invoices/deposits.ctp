<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice[]|\Cake\Collection\CollectionInterface $invoices
 */
?>

<div class="invoices index large-12 medium-8 columns content">
    <h3><?= __('Bookings for my bikes') ?></h3>
    <legend><?= __('My Balance') ?></legend>
    <h3> £<?= $balance ?> </h3>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Invoice Total') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Rental Total') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deposit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deposit_status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Return Deposit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('disputed_amount') ?></th>
                <th scope="col"><?= $this->Paginator->sort('dispute_status') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookedInvoices as $invoice): ?>
            <tr>
                <td><?= $invoice->has('booking') ? $this->Html->link($invoice->booking->id, ['controller' => 'Bookings', 'action' => 'view', $invoice->booking->id]) : '' ?></td>
                <td><?= h($invoice->status) ?></td>
                <td><?= $this->Number->format($invoice->invoice_total) ?></td>
                <td><?= $this->Number->format($invoice->deposit) ?></td>
                <td><?= $this->Number->format($invoice->rental_total) ?></td>
                <td><?= h($invoice->deposit_status) ?></td>
                <td><a href="#" onClick='alert("This booking hasnt been paid")'><?= 'Return' ?></a></td>
                <td><?= $this->Number->format($invoice->disputed_amount) ?></td>
                <td><?= h($invoice->deposit_status) ?></td>
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
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
            <th scope="col"><?= $this->Paginator->sort('status') ?></th>
            <th scope="col"><?= $this->Paginator->sort('Invoice Total') ?></th>
            <th scope="col"><?= $this->Paginator->sort('Rental Total') ?></th>
            <th scope="col"><?= $this->Paginator->sort('deposit') ?></th>
            <th scope="col"><?= $this->Paginator->sort('deposit_status') ?></th>
            <th scope="col"><?= $this->Paginator->sort('Return Deposit') ?></th>
            <th scope="col"><?= $this->Paginator->sort('disputed_amount') ?></th>
            <th scope="col"><?= $this->Paginator->sort('dispute_status') ?></th>
            <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($paidInvoices as $invoice): ?>
            <tr>
                <td><?= $invoice->has('booking') ? $this->Html->link($invoice->booking->id, ['controller' => 'Bookings', 'action' => 'view', $invoice->booking->id]) : '' ?></td>
                <td><?= h($invoice->status) ?></td>
                <td><?= $this->Number->format($invoice->invoice_total) ?></td>
                <td><?= $this->Number->format($invoice->rental_total) ?></td>
                <td><?= $this->Number->format($invoice->deposit) ?></td>
                <td><?= h($invoice->deposit_status) ?></td>
                <?php  if($invoice->deposit_status == 'RETURNED'): ?>
                    <td><a href="#" onClick='alert("This deposit has already been returned")'><?= 'Returned' ?></a></td>
                <?php elseif($invoice->deposit > $balance): ?>
                    <td><a href="#" onClick='alert("You have insufficient funds")'><?= 'Return' ?></a></td>
                <?php elseif($invoice->deposit <= $balance): ?>
                    <td><?= $this->Form->postlink('Return', ['controller' => 'Invoices', 'action' => 'returnDeposit', $invoice->booking->id], ['confirm' => __('Are you sure you want to return invoice #{0} with deposit of £{1} ?',$invoice->booking->id,$invoice->deposit)]) ?></td>
                <?php endif; ?>
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
