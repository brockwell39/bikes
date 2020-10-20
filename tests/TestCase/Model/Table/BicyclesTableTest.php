<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BicyclesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BicyclesTable Test Case
 */
class BicyclesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BicyclesTable
     */
    public $Bicycles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Bicycles',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Bicycles') ? [] : ['className' => BicyclesTable::class];
        $this->Bicycles = TableRegistry::getTableLocator()->get('Bicycles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Bicycles);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
