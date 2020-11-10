<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bicycle $bicycle
 */
?>

<div class="bicycles form large-9 medium-8 columns content">
        <h3><?= __('Search') ?></h3>
    <?= $this->Form->create(null,['url' => ['controller' => 'Bicycles','action' => 'search']]) ?>
    <?php
    //echo $this->Form->create($booking, ['type' => 'file']);
    $start_times = ['09:00'=>'09:00','13:00'=>'13:00'];
    $finish_times = ['12:00'=>'12:00','16:00'=>'16:00'];

    echo $this->Form->control('Start date', ['type' => 'select', 'options' => $week_ahead, 'label' => __('Start date')]);
    echo $this->Form->control('Start time', ['type' => 'select', 'options' => $start_times, 'label' => __('Start time')]);
    echo $this->Form->control('Finish date', ['type' => 'select', 'options' => $week_ahead,'label' => __('Finish date')]);
    echo $this->Form->control('Finish time', ['type' => 'select', 'options' => $finish_times, 'label' => __('Finish time')]);
    echo $this->Form->button(__('Submit'));
    echo $this->Form->end(); ?>
</div>
<div class="bicycles form large-9 medium-8 columns content">
    <table>
        <?php if (isset($search_results)):?>
            <?php if($search_results == []): ?>
            <?php if (isset($search_data)): ?>
                <h3>Sorry there are no bikes available for <?php  echo $search_data['Start_time'].' '. $search_data['Start_date'].' - '.$search_data['Finish_time'].' '.$search_data['Finish_date'] ?></h3>
            <?php endif; ?>
            <?php else: ?>
                <?php if (isset($search_data)): ?>
                    <h3>Bikes available for <?php  echo $search_data['Start_time'].' '. $search_data['Start_date'].' - '.$search_data['Finish_time'].' '.$search_data['Finish_date'] ?></h3>
                <?php endif; ?>
                <table>
                    <thead>
                    <tr>
                        <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('gender') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('gears') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('weekday_price') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('weekend_price') ?></th>
                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>

                <?php foreach ($search_results as $result): ?>
                <tr>
                    <td><?php  echo($result->title) ?> </td>
                    <td><?php  echo($result->gender) ?> </td>
                    <td><?php  echo($result->gears) ?> </td>
                    <td><?php  echo($result->weekday_price) ?> </td>
                    <td><?php  echo($result->weekend_price) ?> </td>
                    <?= $this->Form->Create(__('Book'),['url' => ['controller' => 'Bookings','action' => 'bulkbook']]);
                    echo $this->Form->control('Bike', array('default' => $result->id,'type' => 'hidden'));
                    echo $this->Form->control('Start date', ['default' => $search_data['Start_date'],'type' => 'hidden', 'label' => __('Start date')]);
                    echo $this->Form->control('Start time', ['default' => $search_data['Start_time'],'type' => 'hidden' ,'label'=> __('Start time')]);
                    echo $this->Form->control('Finish date', ['default' => $search_data['Finish_date'],'type' => 'hidden','label' => __('Finish date')]);
                    echo $this->Form->control('Finish time', ['default' => $search_data['Finish_time'],'type' => 'hidden','label' => __('Finish time')]);?>
                    <td><?= $this->Form->button(__('Book'),['type'=>'submit','style' => 'a']); ?></td>
                    <?= $this->Form->end(); ?>
                <?php endforeach; ?>
                </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </table>
</div>
