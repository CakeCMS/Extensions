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

namespace Extensions\Test\TestCase\Controller\Admin;

use Core\Plugin;
use Extensions\Controller\Admin\PluginsController;
use JBZoo\Utils\Arr;
use JBZoo\Utils\Str;
use Cake\ORM\TableRegistry;
use Test\Cases\IntegrationTestCase;

/**
 * Class PluginsControllerTest
 *
 * @package Extensions\Test\TestCase\Controller\Admin
 * @property PluginsController $_controller
 */
class PluginsControllerTest extends IntegrationTestCase
{

    public $fixtures = ['plugin.extensions.extensions'];
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
            'action' => 'apply',
            'name'   => null,
            'slug'   => ''
        ]);

        $viewVars = $this->_controller->viewVars;

        $this->assertResponseContains(__d('extensions', 'The settings could not be saved. Please, try again.'));
        self::assertInstanceOf('Extensions\Model\Entity\Extension', $viewVars['entity']);
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

    public function testIndexActionSuccessRender()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $url = $this->_getUrl([
            'prefix'     => 'admin',
            'plugin'     => $this->_corePlugin,
            'controller' => 'Plugins',
            'action'     => 'index'
        ]);

        $this->get($url);
        $this->assertResponseOk();
        self::assertSame(__d('extensions', 'The list of available plugins'), $this->_controller->viewVars['page_title']);
        self::assertInstanceOf('Cake\ORM\ResultSet', $this->_controller->viewVars['plugins']);
    }

    public function testToggle()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $entityId = 1;

        /** @var \Extensions\Model\Table\ExtensionsTable $table */
        $table  = TableRegistry::get($this->_corePlugin . '.Extensions');
        $entity = $table->get($entityId);

        self::assertSame(1, $entity->status);

        $url = $this->_getUrl([
            'prefix'     => 'admin',
            'plugin'     => $this->_corePlugin,
            'controller' => 'Plugins',
            'action'     => 'toggle',
            $entityId, 1
        ]);

        $this->_request['environment']['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->get($url);

        $plugin = $this->_controller->Extensions->get($entityId);
        self::assertSame(0, $plugin->status);
    }
}
