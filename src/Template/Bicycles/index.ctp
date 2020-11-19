<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bicycle[]|\Cake\Collection\CollectionInterface $bicycles
 */
?>

<div class="bicycles index large-12 medium-8 columns content">
    <h3><?= __('Bicycles') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('gender') ?></th>
                <th scope="col"><?= $this->Paginator->sort('gears') ?></th>
                <th scope="col"><?= $this->Paginator->sort('weekday_price') ?></th>
                <th scope="col"><?= $this->Paginator->sort('weekend_price') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deposit') ?></th>
                <th style="color:#1798A5" scope="col"><?= ('Availibility') ?></th>
                <th style="color:#1798A5" scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bicycles as $bicycle): ?>
            <tr>
                <td><?= h($bicycle->title) ?></td>
                <td><?= h($bicycle->type) ?></td>
                <td><?= h($bicycle->gender) ?></td>
                <td><?= $this->Number->format($bicycle->gears) ?></td>
                <td><?= $this->Number->format($bicycle->weekday_price) ?></td>
                <td><?= $this->Number->format($bicycle->weekend_price) ?></td>
                <td><?= $this->Number->format($bicycle->deposit) ?></td>
                <td><?php foreach ($all_bikes_availiblty[$bicycle->id] as $letter): ?>
                        <?php if($letter == 'A'): ?>
                            <tag id="rcornersA"></tag>
                        <?php elseif($letter == 'B'): ?>
                            <tag id="rcornersB"></tag>
                        <?php elseif($letter == 'P'): ?>
                            <tag id="rcornersP"></tag>
                        <?php endif; ?>
                <?php endforeach; ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $bicycle->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $bicycle->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $bicycle->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bicycle->id)]) ?>
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
