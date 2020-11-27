<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Transaction Entity
 *
 * @property int $id
 * @property int $user
 * @property int $invoice_id
 * @property int $debit
 * @property int $credit
 * @property string $transaction_type
 * @property \Cake\I18n\FrozenTime|null $datetime
 *
 * @property \App\Model\Entity\Invoice $invoice
 */
class Transaction extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user' => true,
        'invoice_id' => true,
        'debit' => true,
        'credit' => true,
        'transaction_type' => true,
        'datetime' => true,
        'invoice' => true,
    ];
}
