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
                'collation' => 'utf8mb4_bin',
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
                'collation' => 'utf8mb4_bin',
                'comment' => '口座名',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('init_record', 'integer', [
                'comment' => '初期資産額',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('admins', [
                'comment' => '管理者情報',
                'collation' => 'utf8mb4_bin',
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
                'collation' => 'utf8mb4_bin',
                'comment' => '名前',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('mail', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'メールアドレス',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'パスワード',
                'default' => null,
                'encoding' => 'utf8mb4',
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
                'collation' => 'utf8mb4_bin',
                'comment' => '二段階認証用シークレットキー',
                'default' => null,
                'encoding' => 'utf8mb4',
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
                'collation' => 'utf8mb4_bin',
                'comment' => 'OpenAPIトークン',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => false,
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
                'collation' => 'utf8mb4_bin',
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
                'null' => false,
            ])
            ->addColumn('is_holiday', 'boolean', [
                'comment' => '休日？',
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('holiday_name', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => '休日名',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('configs', [
                'comment' => '設定',
                'collation' => 'utf8mb4_bin',
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
                'collation' => 'utf8mb4_bin',
                'comment' => '月ごと表示モード',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('display_init_record', 'char', [
                'collation' => 'utf8mb4_bin',
                'comment' => '初期資産額表示',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('record_total_real_color', 'char', [
                'collation' => 'utf8mb4_bin',
                'comment' => '実質資産のチャートカラー',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 6,
                'null' => false,
            ])
            ->addColumn('init_record_color', 'char', [
                'collation' => 'utf8mb4_bin',
                'comment' => '初期資産のチャートカラー',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 6,
                'null' => false,
            ])
            ->addColumn('display_setting', 'json', [
                'comment' => '表示項目設定',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('chromedriver_path', 'string', [
                'collation' => 'utf8mb4_bin',
                'comment' => 'ChromeDriverのパス',
                'default' => null,
                'encoding' => 'utf8mb4',
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('daily_records', [
                'comment' => '資産記録',
                'collation' => 'utf8mb4_bin',
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
                'null' => false,
            ])
            ->addColumn('day', 'date', [
                'comment' => '日付',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('record', 'integer', [
                'comment' => '資産額',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'account_id',
                ]
            )
            ->create();

        $this->table('deposits', [
                'comment' => '入出金',
                'collation' => 'utf8mb4_bin',
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
                'null' => false,
            ])
            ->addColumn('deposit_amount', 'integer', [
                'comment' => '入出金額',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'comment' => '作成日時',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => false,
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
