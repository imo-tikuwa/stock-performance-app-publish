<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CalendarsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CalendarsTable Test Case
 */
class CalendarsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CalendarsTable
     */
    protected $Calendars;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Calendars',
    ];

    /**
     * calendar valid data.
     */
    protected $valid_data;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Calendars') ? [] : ['className' => CalendarsTable::class];
        $this->Calendars = $this->getTableLocator()->get('Calendars', $config);

        $this->valid_data = [
            // 日付
            'day' => date('Y-m-d'),
            // 休日？
            'is_holiday' => '0',
            // 休日名
            'holiday_name' => 'valid data.',
        ];
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Calendars);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $calendar = $this->Calendars->newEmptyEntity();
        $calendar = $this->Calendars->patchEntity($calendar, $this->valid_data);
        $this->assertEmpty($calendar->getErrors());
    }

    /**
     * Test patchEntity method
     *
     * @return void
     */
    public function testPatchEntity(): void
    {
        $calendar = $this->Calendars->get(1);
        $this->assertInstanceOf('\App\Model\Entity\Calendar', $calendar);
        $calendar = $this->Calendars->patchEntity($calendar, $this->valid_data);
        $this->assertInstanceOf('\Cake\Datasource\EntityInterface', $calendar);

        $this->assertFalse($calendar->hasErrors());
    }

    /**
     * Test findByYear method
     *
     * @return void
     */
    public function testFindByYear(): void
    {
        $this->markTestIncomplete('このテストはまだ実装されていません。');
    }

    /**
     * Test findBusinessDays method
     *
     * @return void
     */
    public function testFindBusinessDays(): void
    {
        $this->markTestIncomplete('このテストはまだ実装されていません。');
    }

    /**
     * Test findDisplayTargetDates method
     *
     * @return void
     */
    public function testFindDisplayTargetDates(): void
    {
        $this->markTestIncomplete('このテストはまだ実装されていません。');
    }

    /**
     * Test checkBusinessDay method
     *
     * @return void
     */
    public function testCheckBusinessDay(): void
    {
        $this->markTestIncomplete('このテストはまだ実装されていません。');
    }
}
