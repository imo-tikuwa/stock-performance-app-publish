<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Configs seed.
 */
class ConfigsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'display_only_month' => '02',
                'display_init_record' => '02',
                'record_total_real_color' => '6378FF',
                'init_record_color' => 'FF6363',
                'display_setting' => '["date","record_total_real","prev_day_diff_value","prev_day_diff_rate","beginning_year_diff_value","deposit_day_ammount","record_total","account_records"]',
                'chromedriver_path' => '',
                'created' => '2021-09-17 18:39:21',
                'modified' => '2021-10-06 17:37:29',
            ],
        ];

        $table = $this->table('configs');
        $table->insert($data)->save();
    }
}
