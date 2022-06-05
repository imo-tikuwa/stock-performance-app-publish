<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Phinxlog seed.
 */
class PhinxlogSeed extends AbstractSeed
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
                'version' => 20220605075512,
                'migration_name' => 'Initial',
                'start_time' => '2022-06-05 16:55:12',
                'end_time' => '2022-06-05 16:55:12',
                'breakpoint' => false,
            ],
        ];

        $table = $this->table('phinxlog');
        $table->insert($data)->save();
    }
}
