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

namespace Extensions\Test\TestCase\Controller\Component;

use Cake\Http\ServerRequest;
use Core\TestSuite\TestCase;
use Cake\Controller\ComponentRegistry;
use Extensions\Controller\Admin\PluginsController;
use Extensions\Controller\Component\PluginComponent;

/**
 * Class PluginComponentTest
 *
 * @package Extensions\Test\TestCase\Controller\Component
 */
class PluginComponentTest extends TestCase
{

    public $fixtures = ['plugin.extensions.plugins'];
    protected $_plugin = 'Core';
    protected $_corePlugin = 'Extensions';

    /**
     * @var PluginComponent
     */
    protected $_component;

    public function setUp()
    {
        parent::setUp();

        $request = new ServerRequest([
            'params' => [
                'prefix'     => 'admin',
                'plugin'     => $this->_corePlugin,
                'controller' => 'Plugins'
            ]
        ]);

        $controller = new PluginsController($request);
        $registry   = new ComponentRegistry($controller);
        $this->_component = new PluginComponent($registry);
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->_component);
    }

    public function testGetEntity()
    {
        $entity = $this->_component->getEntity('No-Exists');
        self::assertInstanceOf('Extensions\Model\Entity\Plugin', $entity);
        self::assertSame('no-exists', $entity->slug);

        $entity = $this->_component->getEntity('Community');
        self::assertInstanceOf('Extensions\Model\Entity\Plugin', $entity);
        self::assertSame('community', $entity->slug);
        self::assertSame(1, $entity->status);
    }
}
