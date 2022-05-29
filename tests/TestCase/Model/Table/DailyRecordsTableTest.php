<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CalendarsTable;
use App\Model\Table\DailyRecordsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DailyRecordsTable Test Case
 */
class DailyRecordsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DailyRecordsTable
     */
    protected $DailyRecords;

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
        'app.DailyRecords',
        'app.Accounts',
        'app.Calendars',
    ];

    /**
     * daily_record valid data.
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
        $config = $this->getTableLocator()->exists('DailyRecords') ? [] : ['className' => DailyRecordsTable::class];
        $this->DailyRecords = $this->getTableLocator()->get('DailyRecords', $config);
        $config = $this->getTableLocator()->exists('Calendars') ? [] : ['className' => CalendarsTable::class];
        $this->Calendars = $this->getTableLocator()->get('Calendars', $config);
        $valid_calendar_entity = $this->Calendars->get(1);

        /** @var \App\Model\Entity\Account $account */
        $account = $this->getTableLocator()->get('Accounts')->get(2);

        $this->valid_data = [
            // 口座名
            'account_id' => $account->id,
            // 日付
            'day' => $valid_calendar_entity->day->i18nFormat('yyyy-MM-dd'),
            // 資産額
            'record' => 0,
        ];
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->DailyRecords);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $daily_record = $this->DailyRecords->newEmptyEntity();
        $daily_record = $this->DailyRecords->patchEntity($daily_record, $this->valid_data);
        $this->assertEmpty($daily_record->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $daily_record = $this->DailyRecords->get(1);
        $this->assertTrue($this->DailyRecords->checkRules($daily_record));

        $daily_record = $this->DailyRecords->get(1);
        $daily_record->set('account_id', -1);
        $this->assertFalse($this->DailyRecords->checkRules($daily_record));

        $expected = [
            'account_id' => [
                '_existsIn' => 'This value does not exist',
            ],
        ];
        $this->assertEquals($daily_record->getErrors(), $expected);
    }

    /**
     * Test patchEntity method
     *
     * @return void
     */
    public function testPatchEntity(): void
    {
        $daily_record = $this->DailyRecords->get(1);
        $this->assertInstanceOf('\App\Model\Entity\DailyRecord', $daily_record);
        $daily_record = $this->DailyRecords->patchEntity($daily_record, $this->valid_data);
        $this->assertInstanceOf('\Cake\Datasource\EntityInterface', $daily_record);

        $this->assertFalse($daily_record->hasErrors());
    }

    /**
     * Test getSearchQuery method
     *
     * @return void
     */
    public function testGetSearchQuery(): void
    {
        $query = $this->DailyRecords->getSearchQuery([]);
        $daily_record = $query->select(['id'])->enableHydration(false)->first();

        $this->assertInstanceOf('\Cake\ORM\Query', $query);
        $this->assertTrue(array_key_exists('id', $daily_record));
        $this->assertEquals(1, $daily_record['id']);

        $query = $this->DailyRecords->getSearchQuery(['id' => 99999]);
        $daily_record = $query->enableHydration(false)->first();

        $this->assertInstanceOf('\Cake\ORM\Query', $query);
        $this->assertNull($daily_record);
    }

    /**
     * Test getCsvHeaders method
     *
     * @return void
     */
    public function testGetCsvHeaders(): void
    {
        $data = [
            'ID',
            '口座名',
            '日付',
            '資産額',
            '作成日時',
            '更新日時',
        ];
        $this->assertEquals($this->DailyRecords->getCsvHeaders(), $data);
    }

    /**
     * Test getCsvColumns method
     *
     * @return void
     */
    public function testGetCsvColumns(): void
    {
        $data = [
            'id',
            'account_id',
            'day',
            'record',
            'created',
            'modified',
        ];
        $this->assertEquals($this->DailyRecords->getCsvColumns(), $data);
    }
}
