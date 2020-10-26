<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bicycle $bicycle
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Bicycle'), ['action' => 'edit', $bicycle->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Bicycle'), ['action' => 'delete', $bicycle->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bicycle->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Bicycles'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Bicycle'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="bicycles view large-9 medium-8 columns content">
    <h3><?= h($bicycle->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $bicycle->has('user') ? $this->Html->link($bicycle->user->id, ['controller' => 'Users', 'action' => 'view', $bicycle->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($bicycle->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Type') ?></th>
            <td><?= h($bicycle->type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gender') ?></th>
            <td><?= h($bicycle->gender) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('photo') ?></th>
            <td><?= h($bicycle->photo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Dir') ?></th>
            <td><?= h($bicycle->dir) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gears') ?></th>
            <td><?= $this->Number->format($bicycle->gears) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Weekday Price') ?></th>
            <td><?= $this->Number->format($bicycle->weekday_price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Weekend Price') ?></th>
            <td><?= $this->Number->format($bicycle->weekend_price) ?></td>
        </tr>
    </table>
    <h3>Availibility</h3>
    <table class="vertical-table">
        <tr>
            <th>Time</th>
            <?php for($x=0; $x<7; $x++): ?>
            <th><?= h($slots_ahead[$x]->i18nFormat('MMM dd, yyyy')) ?></th>
            <?php endfor; ?>

        </tr>
        <tr>
            <th>09:00-12:00</th>
                <?php for($x=0; $x<7; $x++): ?>
                <?php if($bookings_to_view[$x] != 'BOOKED'): ?>
                <th> <?= $this->Form->postLink(__('Book'), ['controller'=>'Bookings','action' => 'book',$bicycle->id, $x], ['confirm' => __('Are you sure you want to book {0} on {1}?',$bicycle->title,$bookings_to_view[$x])]) ?> </th>
                <?php elseif($bookings_to_view[$x] == 'BOOKED'):  ?>
                <th><?php echo $bookings_to_view[$x] ?> </th>
                <?php endif; ?>
            <?php endfor; ?>
        </tr>
        <tr>
            <th>13:00-16:00</th>
            <?php for($x=7; $x<14; $x++): ?>
                <?php if($bookings_to_view[$x] != 'BOOKED'): ?>
                    <th> <?= $this->Form->postLink(__('Book'), ['controller'=>'Bookings','action' => 'book',$bicycle->id, $x], ['confirm' => __('Are you sure you want to book {0} on {1}?',$bicycle->title,$bookings_to_view[$x])]) ?> </th>
                <?php elseif($bookings_to_view[$x] == 'BOOKED'):  ?>
                    <th><?php echo $bookings_to_view[$x] ?> </th>
                <?php endif; ?>
            <?php endfor; ?>
        </tr>
    </table>
</div>
