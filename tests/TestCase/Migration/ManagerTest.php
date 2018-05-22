<?php
/**
 * CakeCMS Extensions
 *
 * This file is part of the of the simple cms based on CakePHP 3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     Extensions
 * @license     MIT
 * @copyright   MIT License http://www.opensource.org/licenses/mit-license.php
 * @link        https://github.com/CakeCMS/Extensions".
 * @author      Sergey Kalistratov <kalistratov.s.m@gmail.com>
 */

namespace Extensions\Test\TestCase\Migration;

use Core\Plugin;
use Test\Cases\TestCase;
use Extensions\Migration\Manager as Migration;

/**
 * Class ManagerTest
 *
 * @package Extensions\Test\TestCase\Migration
 */
class ManagerTest extends TestCase
{

    public $fixtures = ['plugin.extensions.phinxlog'];

    public function setUp()
    {
        parent::setUp();

        Plugin::load('Clean',             ['autoload' => true]);
        Plugin::load('Tester',            ['autoload' => true]);
        Plugin::load('Migrate',           ['autoload' => true]);
        Plugin::load('MigrateNoClass',    ['autoload' => true]);
        Plugin::load('MigrateNoInstance', ['autoload' => true]);
    }

    public function tearDown()
    {
        parent::tearDown();

        Plugin::unload('Clean');
        Plugin::unload('Tester');
        Plugin::unload('Migrate');
        Plugin::unload('MigrateNoClass');
        Plugin::unload('MigrateNoInstance');
    }

    public function testCheckIsMigrated()
    {
        $migrate = new Migration('Tester');
        self::assertFalse($migrate->isMigrated(00000));
    }

    public function testGetMigrations()
    {
        $migrate = new Migration('Tester');
        $migrations = $migrate->getMigrations();
        self::assertTrue(is_array($migrations));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetMigrationsClassNoInstance()
    {
        $migrate = new Migration('MigrateNoInstance');
        $migrate->getMigrations();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetMigrationsDuplicateMigration()
    {
        $migrate = new Migration('Migrate');
        $migrate->getMigrations();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetMigrationsNoExistClass()
    {
        $migrate = new Migration('MigrateNoClass');
        $migrate->getMigrations();
    }

    public function testHasMigration()
    {
        $migrate = new Migration('Tester');
        self::assertTrue($migrate->hasMigration());

        $migrate = new Migration('Clean');
        self::assertFalse($migrate->hasMigration());
    }

    public function testMigrateUpSuccess()
    {
        $migrate = new Migration('Tester');
        $output  = $migrate->migrateUp();
        self::assertTrue(is_array($output));
    }

    /**
     * @throws \Aura\Intl\Exception
     */
    public function testMigrationUp()
    {
        $migrate = new Migration('Tester');
        self::assertSame([
            0 => __d(
                'extensions',
                'The version «{0}» of plugin «{1}» has bin success migrated.',
                sprintf('<strong>%s</strong>', 20160613215424),
                sprintf('<strong>%s</strong>', 'Tester')
            )
        ], $migrate->migrateUp());
    }
}
