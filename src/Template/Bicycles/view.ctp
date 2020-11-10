<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bicycle $bicycle
 */
?>

<div class="bicycles view large-12 medium-8 columns content">
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
            <?php for($x=0; $x<14; $x+=2): ?>
            <th><?= h($slots_ahead[$x]->i18nFormat('MMM dd, yyyy')) ?></th>
            <?php endfor; ?>

        </tr>
        <tr>
            <th>09:00-12:00</th>
                <?php for($x=0; $x<14; $x+=2): ?>
                <?php if($bookings_to_view[$x] != 'BOOKED'): ?>
                <th> <?= $this->Form->postLink(__('Book'), ['controller'=>'Bookings','action' => 'book',$bicycle->id, $x], ['confirm' => __('Are you sure you want to book {0} on {1}?',$bicycle->title,$bookings_to_view[$x])]) ?> </th>
                <?php elseif($bookings_to_view[$x] == 'BOOKED'):  ?>
                <th><?php echo $bookings_to_view[$x] ?> </th>
                <?php endif; ?>
            <?php endfor; ?>
        </tr>
        <tr>
            <th>13:00-16:00</th>
            <?php for($x=1; $x<14; $x+=2): ?>
                <?php if($bookings_to_view[$x] != 'BOOKED'): ?>
                    <th> <?= $this->Form->postLink(__('Book'), ['controller'=>'Bookings','action' => 'book',$bicycle->id, $x], ['confirm' => __('Are you sure you want to book {0} on {1}?',$bicycle->title,$bookings_to_view[$x])]) ?> </th>
                <?php elseif($bookings_to_view[$x] == 'BOOKED'):  ?>
                    <th><?php echo $bookings_to_view[$x] ?> </th>
                <?php endif; ?>
            <?php endfor; ?>
        </tr>
    </table>
    <h3>Bulk Booking</h3>
    <div class="bicycles form large-9 medium-8 columns content">
        <?= $this->Form->create(null,['url' => ['controller' => 'Bookings','action' => 'bulkbook']]) ?>
        <?php
        //echo $this->Form->create($booking, ['type' => 'file']);
        $start_times = ['09:00'=>'09:00','13:00'=>'13:00'];
        $finish_times = ['12:00'=>'12:00','16:00'=>'16:00'];

        echo $this->Form->control('Bike', array('default' => $bicycle->id,'type' => 'hidden'));
        echo $this->Form->control('Start date', ['type' => 'select', 'options' => $week_ahead, 'label' => __('Start date')]);
        echo $this->Form->control('Start time', ['type' => 'select', 'options' => $start_times, 'label' => __('Start time')]);
        echo $this->Form->control('Finish date', ['type' => 'select', 'options' => $week_ahead,'label' => __('Finish date')]);
        echo $this->Form->control('Finish time', ['type' => 'select', 'options' => $finish_times, 'label' => __('Finish time')]);
        echo $this->Form->button(__('Submit'));
        echo $this->Form->end(); ?>
    </div>
</div>
