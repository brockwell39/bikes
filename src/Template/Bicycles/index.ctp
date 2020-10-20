<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bicycle[]|\Cake\Collection\CollectionInterface $bicycles
 */
?>
<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Bicycle'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Login'), ['controller' => 'Users', 'action' => 'login']) ?></li>
        <li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></li>
    </ul>
</nav>
<div class="bicycles index large-10 medium-8 columns content">
    <h3><?= __('Bicycles') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('gender') ?></th>
                <th scope="col"><?= $this->Paginator->sort('gears') ?></th>
                <th scope="col"><?= $this->Paginator->sort('photo') ?></th>
                <th scope="col"><?= $this->Paginator->sort('dir') ?></th>
                <th scope="col"><?= $this->Paginator->sort('weekday_price') ?></th>
                <th scope="col"><?= $this->Paginator->sort('weekend_price') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bicycles as $bicycle): ?>
            <tr>
                <td><?= h($bicycle->title) ?></td>
                <td><?= h($bicycle->type) ?></td>
                <td><?= h($bicycle->gender) ?></td>
                <td><?= $this->Number->format($bicycle->gears) ?></td>
                <td><?= h($bicycle->photo) ?></td>
                <td><?= h($bicycle->dir) ?></td>
                <td><?= $this->Number->format($bicycle->weekday_price) ?></td>
                <td><?= $this->Number->format($bicycle->weekend_price) ?></td>
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
