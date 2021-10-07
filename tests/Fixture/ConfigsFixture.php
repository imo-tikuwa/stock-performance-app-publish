<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ConfigsFixture
 */
class ConfigsFixture extends TestFixture
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
        'display_only_month' => [
            'type' => 'char',
            'length' => 2,
            'null' => true,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => '月ごと表示モード',
            'precision' => null,
        ],
        'display_init_record' => [
            'type' => 'char',
            'length' => 2,
            'null' => true,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => '初期資産額表示',
            'precision' => null,
        ],
        'record_total_real_color' => [
            'type' => 'char',
            'length' => 6,
            'null' => true,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => '実質資産のチャートカラー',
            'precision' => null,
        ],
        'init_record_color' => [
            'type' => 'char',
            'length' => 6,
            'null' => true,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => '初期資産のチャートカラー',
            'precision' => null,
        ],
        'display_setting' => [
            'type' => 'json',
            'length' => null,
            'null' => true,
            'default' => null,
            'comment' => '表示項目設定',
            'precision' => null,
        ],
        'chromedriver_path' => [
            'type' => 'string',
            'length' => 255,
            'null' => true,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => 'ChromeDriverのパス',
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
                'display_only_month' => '01',
                'display_init_record' => '01',
                'record_total_real_color' => '6378FF',
                'init_record_color' => 'FF6363',
                'display_setting' => null,
                'chromedriver_path' => '',
                'created' => '2021-09-19 15:47:02',
                'modified' => '2021-09-19 15:47:02',
            ],
        ];
        parent::init();
    }
}
