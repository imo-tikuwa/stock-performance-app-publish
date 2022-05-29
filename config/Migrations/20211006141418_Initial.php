<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    public $autoId = false;

    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up()
    {
        $this->table('accounts', [
                'comment' => '口座',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'comment' => '口座名',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('init_record', 'integer', [
                'comment' => '初期資産額',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('admins', [
                'comment' => '管理者情報',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'comment' => '名前',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('mail', 'string', [
                'comment' => 'メールアドレス',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'comment' => 'パスワード',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('use_otp', 'boolean', [
                'comment' => '二段階認証を使用する？',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('otp_secret', 'string', [
                'comment' => '二段階認証用シークレットキー',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('privilege', 'json', [
                'comment' => '権限',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('api_token', 'string', [
                'comment' => 'OpenAPIトークン',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted', 'datetime', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('calendars', [
                'comment' => 'カレンダー',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('day', 'date', [
                'comment' => '日付',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('is_holiday', 'boolean', [
                'comment' => '休日？',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('holiday_name', 'string', [
                'comment' => '休日名',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('configs', [
                'comment' => '設定',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('display_only_month', 'char', [
                'comment' => '月ごと表示モード',
                'default' => null,
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('display_init_record', 'char', [
                'comment' => '初期資産額表示',
                'default' => null,
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('record_total_real_color', 'char', [
                'comment' => '実質資産のチャートカラー',
                'default' => null,
                'limit' => 6,
                'null' => true,
            ])
            ->addColumn('init_record_color', 'char', [
                'comment' => '初期資産のチャートカラー',
                'default' => null,
                'limit' => 6,
                'null' => true,
            ])
            ->addColumn('display_setting', 'json', [
                'comment' => '表示項目設定',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('chromedriver_path', 'string', [
                'comment' => 'ChromeDriverのパス',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('daily_records', [
                'comment' => '資産記録',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('account_id', 'integer', [
                'comment' => '口座名',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('day', 'date', [
                'comment' => '日付',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('record', 'integer', [
                'comment' => '資産額',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'account_id',
                ]
            )
            ->create();

        $this->table('deposits', [
                'comment' => '入出金',
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'ID',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('deposit_date', 'date', [
                'comment' => '入出金日',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deposit_amount', 'integer', [
                'comment' => '入出金額',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted', 'datetime', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down()
    {
        $this->table('accounts')->drop()->save();
        $this->table('admins')->drop()->save();
        $this->table('calendars')->drop()->save();
        $this->table('configs')->drop()->save();
        $this->table('daily_records')->drop()->save();
        $this->table('deposits')->drop()->save();
    }
}
