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
use JBZoo\Utils\Arr;
use JBZoo\Utils\Str;
use Cake\ORM\TableRegistry;
use Test\Cases\IntegrationTestCase;
use Extensions\Controller\Admin\PluginsController;

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

        Plugin::load('Clean', ['autoload' => true]);
        Plugin::load('Tester', ['autoload' => true]);
    }

    public function tearDown()
    {
        parent::tearDown();

        Plugin::unload('Clean');
        Plugin::unload('Tester');
    }

    /**
     * @throws \PHPUnit\Exception
     */
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

    /**
     * @throws \Aura\Intl\Exception
     * @throws \PHPUnit\Exception
     */
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

    /**
     * @throws \PHPUnit\Exception
     */
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

    /**
     * @throws \Aura\Intl\Exception
     * @throws \PHPUnit\Exception
     * TODO check Search plugin for support cakephp 3.6
     */
    /*public function testIndexActionSuccessRender()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->get([
            'prefix'     => 'admin',
            'action'     => 'index',
            'controller' => 'Plugins',
            'plugin'     => $this->_corePlugin
        ]);

       self::assertSame(__d('extensions', 'The list of available plugins'), $this->_controller->viewVars['page_title']);
       self::assertInstanceOf('Cake\ORM\ResultSet', $this->_controller->viewVars['plugins']);
    }*/

    /**
     * @throws \PHPUnit\Exception
     */
    public function testMigrateNoPlugin()
    {
        $this->get([
            'prefix'     => 'admin',
            'controller' => 'Plugins',
            'action'     => 'migrate',
            'plugin'     => $this->_corePlugin,
            'noExist'
        ]);

        $this->assertRedirect([
            'prefix'     => 'admin',
            'controller' => 'Plugins',
            'action'     => 'index',
            'plugin'     => $this->_corePlugin
        ]);
    }

    /**
     * @throws \PHPUnit\Exception
     */
    public function testMigrationFailNotFoundMigrations()
    {
        $this->get([
            'prefix'     => 'admin',
            'controller' => 'Plugins',
            'action'     => 'migrate',
            'plugin'     => $this->_corePlugin,
            'Clean'
        ]);

        $session = $this->_controller->request->getSession()->read('Flash.flash');

        $this->assertRedirect([
            'prefix'     => 'admin',
            'controller' => 'Plugins',
            'action'     => 'index',
            'plugin'     => $this->_corePlugin
        ]);

        self::assertSame([[
            'message'   => 'Not found migration for «<strong>Clean</strong>»',
            'key'       => 'flash',
            'element'   => 'Flash/error',
            'params'    => []
        ]], $session);
    }

    /**
     * @throws \PHPUnit\Exception
     */
    public function testMigrationSuccess()
    {
        Plugin::load('TestPlugin', ['autoload' => true]);

        $this->get([
            'prefix'     => 'admin',
            'controller' => 'Plugins',
            'action'     => 'migrate',
            'plugin'     => $this->_corePlugin,
            'TestPlugin'
        ]);

        $session = $this->_controller->request->getSession()->read('Flash.flash');

        $this->assertRedirect([
            'prefix'     => 'admin',
            'controller' => 'Plugins',
            'action'     => 'index',
            'plugin'     => $this->_corePlugin
        ]);

        self::assertSame([[
            'message'   => 'The version «<strong>20170613211616</strong>» of plugin «<strong>TestPlugin</strong>» has bin success migrated.',
            'key'       => 'flash',
            'element'   => 'Flash/success',
            'params'    => []
        ]], $session);

        Plugin::unload('TestPlugin');
    }

    /**
     * @throws \PHPUnit\Exception
     */
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
