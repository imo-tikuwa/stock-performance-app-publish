<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AccountsFixture
 */
class AccountsFixture extends TestFixture
{
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
                'name' => 'SBI証券',
                'init_record' => 3000000,
                'created' => '2021-09-16 21:30:44',
                'modified' => '2021-09-16 21:30:44',
            ],
            [
                'id' => 2,
                'name' => '楽天証券',
                'init_record' => 500000,
                'created' => '2021-09-16 21:30:44',
                'modified' => '2021-09-16 21:30:44',
            ],
        ];
        parent::init();
    }
}
