<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
?>

<div class="bookings view large-9 medium-8 columns content">
    <h3><?= h($booking->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $booking->has('user') ? $this->Html->link($booking->user->id, ['controller' => 'Users', 'action' => 'view', $booking->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bicycle') ?></th>
            <td><?= $booking->has('bicycle') ? $this->Html->link($booking->bicycle->title, ['controller' => 'Bicycles', 'action' => 'view', $booking->bicycle->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= h($booking->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($booking->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($booking->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Booking Start') ?></th>
            <td><?= h($booking->booking_start) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Booking End') ?></th>
            <td><?= h($booking->booking_end) ?></td>
        </tr>
    </table>
</div>
