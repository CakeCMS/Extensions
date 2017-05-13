<?php
/**
 * CakeCMS Extensions
 *
 * This file is part of the of the simple cms based on CakePHP 3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   Extensions
 * @license   MIT
 * @copyright MIT License http://www.opensource.org/licenses/mit-license.php
 * @link      https://github.com/CakeCMS/Extensions".
 * @author    Sergey Kalistratov <kalistratov.s.m@gmail.com>
 */

namespace Extensions\Test\TestCase\Controller\Admin;

use Core\Plugin;
use Core\TestSuite\IntegrationTestCase;
use JBZoo\Utils\Arr;
use JBZoo\Utils\Str;

/**
 * Class PluginsControllerTest
 *
 * @package Extensions\Test\TestCase\Controller\Admin
 */
class PluginsControllerTest extends IntegrationTestCase
{

    public $fixtures = ['plugin.extensions.plugins'];
    protected $_corePlugin = 'Extensions';

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

    public function testConfigMissingPlugin()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $url = $this->_getUrl([
            'prefix'     => 'admin',
            'plugin'     => $this->_corePlugin,
            'controller' => 'Plugins',
            'action'     => 'config',
            'no-exists'
        ]);

        $this->get($url);
        $this->assertResponseCode(500);
    }

    public function testConfigSaveFail()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $url = $this->_getUrl([
            'prefix'     => 'admin',
            'plugin'     => $this->_corePlugin,
            'controller' => 'Plugins',
            'action'     => 'config',
            'tester'
        ]);

        $this->post($url, [
            'action' => 'plugin.apply',
            'name'   => null,
            'slug'   => ''
        ]);

        $viewVars = $this->_controller->viewVars;

        $this->assertResponseContains(__d('extensions', 'The settings could not be saved. Please, try again.'));
        self::assertInstanceOf('Extensions\Model\Entity\Plugin', $viewVars['entity']);
        self::assertTrue(Arr::key('page_title', $viewVars));
        self::assertTrue(Arr::key('plugin', $viewVars));
        self::assertSame('Tester', $viewVars['plugin']);
    }

    public function testConfigSaveSuccess()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $url = $this->_getUrl([
            'prefix'     => 'admin',
            'plugin'     => $this->_corePlugin,
            'controller' => 'Plugins',
            'action'     => 'config',
            Str::low($this->_corePlugin)
        ]);

        $this->post($url, [
            'action' => 'plugin.apply',
            'params' => [
                'new-param' => 'Test value'
            ]
        ]);

        $this->assertRedirect([
            'prefix'     => 'admin',
            'plugin'     => $this->_corePlugin,
            'controller' => 'Plugins',
            'action'     => 'config',
            Str::low($this->_corePlugin)
        ]);
    }
}
