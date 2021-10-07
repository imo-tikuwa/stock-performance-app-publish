<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AdminsFixture
 */
class AdminsFixture extends TestFixture
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
        'name' => [
            'type' => 'string',
            'length' => 255,
            'null' => false,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => '名前',
            'precision' => null,
        ],
        'mail' => [
            'type' => 'string',
            'length' => 255,
            'null' => false,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => 'メールアドレス',
            'precision' => null,
        ],
        'password' => [
            'type' => 'string',
            'length' => 255,
            'null' => false,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => 'パスワード',
            'precision' => null,
        ],
        'use_otp' => [
            'type' => 'boolean',
            'length' => null,
            'null' => true,
            'default' => '0',
            'comment' => '二段階認証を使用する？',
            'precision' => null,
        ],
        'otp_secret' => [
            'type' => 'string',
            'length' => 255,
            'null' => true,
            'default' => null,
            'collate' => 'utf8_general_ci',
            'comment' => '二段階認証用シークレットキー',
            'precision' => null,
        ],
        'privilege' => [
            'type' => 'json',
            'length' => null,
            'null' => true,
            'default' => null,
            'comment' => '権限',
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
        'deleted' => [
            'type' => 'datetime',
            'length' => null,
            'precision' => null,
            'null' => true,
            'default' => null,
            'comment' => '削除日時',
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
                'name' => '管理者',
                'mail' => 'admin@imo-tikuwa.com',
                'password' => 'ryEPIdJfxaYeJPYwpPLeMQ==:eo3lZUNukBC0+rIp3t1xaw==',
                'use_otp' => false,
                'otp_secret' => null,
                'privilege' => null,
                'created' => '2021-09-05 10:33:11',
                'modified' => '2021-09-05 10:33:11',
                'deleted' => null,
            ],
        ];
        parent::init();
    }
}
