<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * Account Entity
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $init_record
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\DailyRecord[] $daily_records
 */
class Account extends AppEntity
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
        'name' => true,
        'init_record' => true,
        'created' => true,
        'modified' => true,
        'daily_records' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'created',
        'modified',
    ];
}
