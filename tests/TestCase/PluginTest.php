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

namespace Extensions\Test\TestCase;

use Extensions\Plugin;
use Test\Cases\TestCase;
use Extensions\Model\Entity\Extension;

/**
 * Class PluginTest
 *
 * @package Extensions\Test\TestCase
 */
class PluginTest extends TestCase
{

    public $fixtures        = ['plugin.extensions.extensions'];

    protected $_corePlugin  = 'Extensions';
    protected $_plugin      = 'Core';

    public function setUp()
    {
        parent::setUp();
        Plugin::load('Community');
    }

    public function tearDown()
    {
        parent::tearDown();
        Plugin::unload('Community');
    }

    public static function testGetInstance()
    {
        /** @var Extension $plugin */
        $plugin = Plugin::getInstance('Community');

        self::assertInstanceOf('Extensions\Model\Entity\Extension', $plugin);
        self::assertSame('Community', $plugin->name);
    }

    /**
     * @expectedException \Cake\Core\Exception\MissingPluginException
     */
    public function testGetInstanceNotFindPlugin()
    {
        Plugin::getInstance('TestNo');
    }

    public function testGetParams()
    {
        $params = Plugin::getParams('Community');
        self::assertInstanceOf('JBZoo\Data\JSON', $params);
    }
}
