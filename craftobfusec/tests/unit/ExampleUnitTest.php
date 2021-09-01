<?php
/**
 * Test Plugin plugin for Craft CMS 3.x
 *
 * This is a Test Plugin
 *
 * @link      Me@me.me
 * @copyright Copyright (c) 2021 Me
 */

namespace developer\testplugintests\unit;

use Codeception\Test\Unit;
use UnitTester;
use Craft;
use developer\testplugin\TestPlugin;

/**
 * ExampleUnitTest
 *
 *
 * @author    Me
 * @package   TestPlugin
 * @since     1.0.0
 */
class ExampleUnitTest extends Unit
{
    // Properties
    // =========================================================================

    /**
     * @var UnitTester
     */
    protected $tester;

    // Public methods
    // =========================================================================

    // Tests
    // =========================================================================

    /**
     *
     */
    public function testPluginInstance()
    {
        $this->assertInstanceOf(
            TestPlugin::class,
            TestPlugin::$plugin
        );
    }

    /**
     *
     */
    public function testCraftEdition()
    {
        Craft::$app->setEdition(Craft::Pro);

        $this->assertSame(
            Craft::Pro,
            Craft::$app->getEdition()
        );
    }
}
