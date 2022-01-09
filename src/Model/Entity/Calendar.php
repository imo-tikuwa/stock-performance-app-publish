<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * Calendar Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenDate|null $day
 * @property bool|null $is_holiday
 * @property string|null $holiday_name
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Calendar extends AppEntity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<bool>
     */
    protected $_accessible = [
        'day' => true,
        'is_holiday' => true,
        'holiday_name' => true,
        'created' => true,
        'modified' => true,
    ];
}
