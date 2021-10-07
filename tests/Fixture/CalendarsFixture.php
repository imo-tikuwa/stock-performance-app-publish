<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CalendarsFixture
 */
class CalendarsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id' => [
            'type' => 'integer',
            'length' => null,
            'unsigned' => false,
            'null' => false,
            'default' => null,
            'comment' => 'ID',
            'autoIncrement' => true,
            'precision' => null,
        ],
        'day' => [
            'type' => 'date',
            'length' => null,
            'null' => true,
            'default' => null,
            'comment' => '日付',
            'precision' => null,
        ],
        'is_holiday' => [
            'type' => 'boolean',
            'length' => null,
            'null' => true,
            'default' => '0',
            'comment' => '休日？',
            'precision' => null,
        ],
        'holiday_name' => [
            'type' => 'string',
            'length' => 255,
            'null' => true,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => '休日名',
            'precision' => null,
        ],
        'created' => [
            'type' => 'datetime',
            'length' => null,
            'precision' => null,
            'null' => true,
            'default' => null,
            'comment' => '作成日時',
        ],
        'modified' => [
            'type' => 'datetime',
            'length' => null,
            'precision' => null,
            'null' => true,
            'default' => null,
            'comment' => '更新日時',
        ],
        '_constraints' => [
            'primary' => [
                'type' => 'primary',
                'columns' => [
                    'id',
                ],
                'length' => [
                ],
            ],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci',
        ],
    ];

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'day' => '2021-09-10',
                'is_holiday' => 0,
                'holiday_name' => '',
                'created' => '2021-09-10 23:45:01',
                'modified' => '2021-09-10 23:45:01',
            ],
            [
                'id' => 2,
                'day' => '2021-09-11',
                'is_holiday' => 1,
                'holiday_name' => '',
                'created' => '2021-09-10 23:45:01',
                'modified' => '2021-09-10 23:45:01',
            ],
        ];
        parent::init();
    }
}
