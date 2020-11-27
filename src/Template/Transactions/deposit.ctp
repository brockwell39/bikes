<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Transaction $transaction
 */
?>

<div class="transactions form large-9 medium-8 columns content">

    <?= $this->Form->create($transaction) ?>
    <fieldset>
        <legend><?= __('My Balance') ?></legend>
        <h3> £<?= $balance ?> </h3>
        <legend><?= __('Make A Deposit') ?></legend>
        <br>
        <?php
        echo $this->Form->hidden('invoice_id',['value' => 'NULL']);
        echo $this->Form->hidden('transaction_type',['value' => 'Deposit']);
        echo $this->Form->control('Name on Card',['required']);
        echo $this->Form->control('Card Number');
        echo $this->Form->control('Expiry Date');
        echo $this->Form->control('Cvv');
        echo $this->Form->control('debit',['label'=>'Amount to deposit']);

        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
