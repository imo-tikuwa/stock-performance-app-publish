<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ConfigsFixture
 */
class ConfigsFixture extends TestFixture
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
                'display_only_month' => '01',
                'display_init_record' => '01',
                'record_total_real_color' => '',
                'init_record_color' => '',
                'display_setting' => null,
                'chromedriver_path' => 'Lorem ipsum dolor sit amet',
                'created' => '2021-09-19 15:47:02',
                'modified' => '2021-09-19 15:47:02',
            ],
        ];
        parent::init();
    }
}
