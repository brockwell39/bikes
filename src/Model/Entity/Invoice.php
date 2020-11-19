<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Invoice Entity
 *
 * @property int $id
 * @property int $booking_id
 * @property string $status
 * @property int $weekday_amount
 * @property string $weekday_quantity
 * @property int $weekend_amount
 * @property int $weekend_quantity
 * @property int $deposit
 * @property string $deposit_status
 * @property int $disputed_amount
 * @property string $dispute_status
 *
 * @property \App\Model\Entity\Booking $booking
 */
class Invoice extends Entity
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
        'booking_id' => true,
        'status' => true,
        'weekday_amount' => true,
        'weekday_quantity' => true,
        'weekend_amount' => true,
        'weekend_quantity' => true,
        'deposit' => true,
        'deposit_status' => true,
        'disputed_amount' => true,
        'dispute_status' => true,
        'booking' => true,
    ];
}
