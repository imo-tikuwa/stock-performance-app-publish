<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CalendarsFixture
 */
class CalendarsFixture extends TestFixture
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
                'day' => '2021-09-10',
                'is_holiday' => 0,
                'holiday_name' => '',
                'created' => '2021-09-10 23:45:01',
                'modified' => '2021-09-10 23:45:01',
            ],
            [
                'id' => 2,
                'day' => '2021-09-11',
                'is_holiday' => 1,
                'holiday_name' => '',
                'created' => '2021-09-10 23:45:01',
                'modified' => '2021-09-10 23:45:01',
            ],
        ];
        parent::init();
    }
}
