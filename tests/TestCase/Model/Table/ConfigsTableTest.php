<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConfigsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConfigsTable Test Case
 */
class ConfigsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ConfigsTable
     */
    protected $Configs;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Configs',
    ];

    /**
     * config valid data.
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
        $config = $this->getTableLocator()->exists('Configs') ? [] : ['className' => ConfigsTable::class];
        $this->Configs = $this->getTableLocator()->get('Configs', $config);

        $this->valid_data = [
            // 月ごと表示モード
            'display_only_month' => '01',
            // 初期資産額表示
            'display_init_record' => '01',
            // 実質資産のチャートカラー
            'record_total_real_color' => 'FFFFFF',
            // 初期資産のチャートカラー
            'init_record_color' => 'FFFFFF',
            // 表示項目設定
            'display_setting' => [
                'date',
                'record_total_real'
            ],
            // ChromeDriverのパス
            'chromedriver_path' => 'valid data.',
        ];
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Configs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $config = $this->Configs->newEmptyEntity();
        $config = $this->Configs->patchEntity($config, $this->valid_data);
        $this->assertEmpty($config->getErrors());
    }

    /**
     * Test patchEntity method
     *
     * @return void
     */
    public function testPatchEntity(): void
    {
        $config = $this->Configs->get(1);
        $this->assertInstanceOf('\App\Model\Entity\Config', $config);
        $config = $this->Configs->patchEntity($config, $this->valid_data);
        $this->assertInstanceOf('\Cake\Datasource\EntityInterface', $config);

        $this->assertFalse($config->hasErrors());
    }
}
