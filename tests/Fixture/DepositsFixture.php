<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DepositsFixture
 */
class DepositsFixture extends TestFixture
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
                'deposit_date' => '2021-09-06',
                'deposit_amount' => -100000000,
                'created' => '2021-09-06 20:37:36',
                'modified' => '2021-09-06 20:37:36',
                'deleted' => null,
            ],
        ];
        parent::init();
    }
}
