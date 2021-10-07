<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DailyRecordsFixture
 */
class DailyRecordsFixture extends TestFixture
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
        'account_id' => [
            'type' => 'integer',
            'length' => null,
            'unsigned' => false,
            'null' => true,
            'default' => null,
            'comment' => '口座名',
            'precision' => null,
            'autoIncrement' => null,
        ],
        'day' => [
            'type' => 'date',
            'length' => null,
            'null' => true,
            'default' => null,
            'comment' => '日付',
            'precision' => null,
        ],
        'record' => [
            'type' => 'integer',
            'length' => null,
            'unsigned' => false,
            'null' => true,
            'default' => null,
            'comment' => '資産額',
            'precision' => null,
            'autoIncrement' => null,
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
        '_indexes' => [
            'account_id' => [
                'type' => 'index',
                'columns' => [
                    'account_id',
                ],
                'length' => [
                ],
            ],
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
                'account_id' => 1,
                'day' => '2021-09-16',
                'record' => 0,
                'created' => '2021-09-16 21:30:45',
                'modified' => '2021-09-16 21:30:45',
            ],
        ];
        parent::init();
    }
}
