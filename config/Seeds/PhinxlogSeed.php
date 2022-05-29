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
                'version' => 20211006141418,
                'migration_name' => 'Initial',
                'start_time' => '2021-10-06 23:14:18',
                'end_time' => '2021-10-06 23:14:18',
                'breakpoint' => false,
            ],
        ];

        $table = $this->table('phinxlog');
        $table->insert($data)->save();
    }
}
