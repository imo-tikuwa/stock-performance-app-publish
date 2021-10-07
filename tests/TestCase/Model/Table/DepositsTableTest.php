<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CalendarsTable;
use App\Model\Table\DepositsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DepositsTable Test Case
 */
class DepositsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CalendarsTable
     */
    protected $Calendars;

    /**
     * Test subject
     *
     * @var \App\Model\Table\DepositsTable
     */
    protected $Deposits;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Deposits',
        'app.Calendars',
    ];

    /**
     * deposit valid data.
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
        $config = $this->getTableLocator()->exists('Deposits') ? [] : ['className' => DepositsTable::class];
        $this->Deposits = $this->getTableLocator()->get('Deposits', $config);
        $config = $this->getTableLocator()->exists('Calendars') ? [] : ['className' => CalendarsTable::class];
        $this->Calendars = $this->getTableLocator()->get('Calendars', $config);
        $valid_calendar_entity = $this->Calendars->get(1);

        $this->valid_data = [
            // 入出金日
            'deposit_date' => $valid_calendar_entity->day->i18nFormat('yyyy-MM-dd'),
            // 入出金額
            'deposit_amount' => -100000000,
        ];
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Deposits);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $deposit = $this->Deposits->newEmptyEntity();
        $deposit = $this->Deposits->patchEntity($deposit, $this->valid_data);
        $this->assertEmpty($deposit->getErrors());
    }

    /**
     * Test patchEntity method
     *
     * @return void
     */
    public function testPatchEntity(): void
    {
        $deposit = $this->Deposits->get(1);
        $this->assertInstanceOf('\App\Model\Entity\Deposit', $deposit);
        $deposit = $this->Deposits->patchEntity($deposit, $this->valid_data);
        $this->assertInstanceOf('\Cake\Datasource\EntityInterface', $deposit);

        $this->assertFalse($deposit->hasErrors());
    }

    /**
     * Test getSearchQuery method
     *
     * @return void
     */
    public function testGetSearchQuery(): void
    {
        $query = $this->Deposits->getSearchQuery([]);
        $deposit = $query->select(['id'])->enableHydration(false)->first();

        $this->assertInstanceOf('\Cake\ORM\Query', $query);
        $this->assertTrue(array_key_exists('id', $deposit));
        $this->assertEquals(1, $deposit['id']);

        $query = $this->Deposits->getSearchQuery(['id' => 99999]);
        $deposit = $query->enableHydration(false)->first();

        $this->assertInstanceOf('\Cake\ORM\Query', $query);
        $this->assertNull($deposit);
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
            '入出金日',
            '入出金額',
            '作成日時',
            '更新日時',
        ];
        $this->assertEquals($this->Deposits->getCsvHeaders(), $data);
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
            'deposit_date',
            'deposit_amount',
            'created',
            'modified',
        ];
        $this->assertEquals($this->Deposits->getCsvColumns(), $data);
    }

    /**
     * Test query method
     *
     * @return void
     */
    public function testQuery(): void
    {
        $query = $this->Deposits->query();
        $this->assertInstanceOf('\SoftDelete\ORM\Query', $query);
    }

    /**
     * Test deleteAll method
     *
     * @return void
     */
    public function testDeleteAll(): void
    {
        $this->Deposits->deleteAll([]);
        $this->assertEquals(0, $this->Deposits->find()->count());
        $this->assertNotEquals(0, $this->Deposits->find('all', ['withDeleted'])->count());
    }

    /**
     * Test getSoftDeleteField method
     *
     * @return void
     */
    public function testGetSoftDeleteField(): void
    {
        $this->assertEquals($this->Deposits->getSoftDeleteField(), 'deleted');
    }

    /**
     * Test hardDelete method
     *
     * @return void
     */
    public function testHardDelete(): void
    {
        $deposit = $this->Deposits->get(1);
        $this->Deposits->hardDelete($deposit);
        $deposit = $this->Deposits->findById(1)->first();
        $this->assertEquals(null, $deposit);

        $deposit = $this->Deposits->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->assertEquals(null, $deposit);
    }

    /**
     * Test hardDeleteAll method
     *
     * @return void
     */
    public function testHardDeleteAll(): void
    {
        $affected_rows = $this->Deposits->hardDeleteAll(new \DateTime('now'));
        $this->assertEquals(0, $affected_rows);

        $deposits_rows_count = $this->Deposits->find('all', ['withDeleted'])->count();

        $this->Deposits->delete($this->Deposits->get(1));
        $affected_rows = $this->Deposits->hardDeleteAll(new \DateTime('now'));
        $this->assertEquals(1, $affected_rows);

        $newdeposits_rows_count = $this->Deposits->find('all', ['withDeleted'])->count();
        $this->assertEquals($deposits_rows_count - 1, $newdeposits_rows_count);
    }

    /**
     * Test restore method
     *
     * @return void
     */
    public function testRestore(): void
    {
        $deposit = $this->Deposits->findById(1)->first();
        $this->assertNotNull($deposit);
        $this->Deposits->delete($deposit);
        $deposit = $this->Deposits->findById(1)->first();
        $this->assertNull($deposit);

        $deposit = $this->Deposits->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->Deposits->restore($deposit);
        $deposit = $this->Deposits->findById(1)->first();
        $this->assertNotNull($deposit);
    }
}
