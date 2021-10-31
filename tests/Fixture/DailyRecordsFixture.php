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
