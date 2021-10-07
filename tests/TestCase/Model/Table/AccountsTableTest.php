<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AccountsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AccountsTable Test Case
 */
class AccountsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AccountsTable
     */
    protected $Accounts;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Accounts',
        'app.DailyRecords',
    ];

    /**
     * account valid data.
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
        $config = $this->getTableLocator()->exists('Accounts') ? [] : ['className' => AccountsTable::class];
        $this->Accounts = $this->getTableLocator()->get('Accounts', $config);

        $this->valid_data = [
            // 口座名
            'name' => 'valid data.',
            // 初期資産額
            'init_record' => 0,
        ];
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Accounts);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $account = $this->Accounts->newEmptyEntity();
        $account = $this->Accounts->patchEntity($account, $this->valid_data);
        $this->assertEmpty($account->getErrors());
    }

    /**
     * Test patchEntity method
     *
     * @return void
     */
    public function testPatchEntity(): void
    {
        $account = $this->Accounts->get(1);
        $this->assertInstanceOf('\App\Model\Entity\Account', $account);
        $account = $this->Accounts->patchEntity($account, $this->valid_data);
        $this->assertInstanceOf('\Cake\Datasource\EntityInterface', $account);

        $this->assertFalse($account->hasErrors());
    }

    /**
     * Test getSearchQuery method
     *
     * @return void
     */
    public function testGetSearchQuery(): void
    {
        $query = $this->Accounts->getSearchQuery([]);
        $account = $query->select(['id'])->enableHydration(false)->first();

        $this->assertInstanceOf('\Cake\ORM\Query', $query);
        $this->assertTrue(array_key_exists('id', $account));
        $this->assertEquals(1, $account['id']);

        $query = $this->Accounts->getSearchQuery(['id' => 99999]);
        $account = $query->enableHydration(false)->first();

        $this->assertInstanceOf('\Cake\ORM\Query', $query);
        $this->assertNull($account);
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
            '初期資産額',
            '作成日時',
            '更新日時',
        ];
        $this->assertEquals($this->Accounts->getCsvHeaders(), $data);
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
            'name',
            'init_record',
            'created',
            'modified',
        ];
        $this->assertEquals($this->Accounts->getCsvColumns(), $data);
    }
}
