<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice $invoice
 */
?>
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
            <th scope="row"><?= __('Weekday Amount') ?></th>
            <td><?= $this->Number->format($invoice->weekday_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Weekend Quantity') ?></th>
            <td><?= $this->Number->format($invoice->weekend_quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Weekend Amount') ?></th>
            <td><?= $this->Number->format($invoice->weekend_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Invoice Total') ?></th>
            <td><?= $this->Number->format($invoice->invoiceTotal) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deposit') ?></th>
            <td><?= $this->Number->format($invoice->deposit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deposit Status') ?></th>
            <td><?= h($invoice->deposit_status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Disputed Amount') ?></th>
            <td><?= $this->Number->format($invoice->disputed_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Dispute Status') ?></th>
            <td><?= h($invoice->dispute_status) ?></td>
        </tr>
    </table>
</div>
