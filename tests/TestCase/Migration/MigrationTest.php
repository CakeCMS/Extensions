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
use JBZoo\Utils\FS;
use Test\Cases\TestCase;
use Extensions\Migration\Migration;

/**
 * Class MigrationTest
 *
 * @package Extensions\Test\TestCase\Migration
 */
class MigrationTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        Plugin::load('Tester', ['autoload' => true]);
    }

    public function tearDown()
    {
        parent::tearDown();
        Plugin::unload('Tester');
    }

    /**
     * @expectedException \Cake\Core\Exception\MissingPluginException
     */
    public function testFailGetPath()
    {
        $migrate = new Migration('NoExits');
        $migrate->getPath();
    }

    public function testGetData()
    {
        $migrate = new Migration('Tester');
        self::assertTrue(is_array($migrate->getData()));
    }

    public function testGetManager()
    {
        $migrate = new Migration('Tester');
        $manager = $migrate->getManager();
        self::assertInstanceOf('\Extensions\Migration\Manager', $manager);
    }
}
