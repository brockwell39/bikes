<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Bicycle Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $type
 * @property string $gender
 * @property int $gears
 * @property string $photo
 * @property string $dir
 * @property int $weekday_price
 * @property int $weekend_price
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Bicycle extends Entity
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
        'user_id' => true,
        'title' => true,
        'type' => true,
        'gender' => true,
        'gears' => true,
        'photo' => true,
        'dir' => true,
        'weekday_price' => true,
        'weekend_price' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'deposit' => true,
    ];
}
