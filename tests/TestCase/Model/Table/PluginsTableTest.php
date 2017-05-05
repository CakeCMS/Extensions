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

namespace Extensions\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Core\TestSuite\TestCase;
use Extensions\Model\Table\PluginsTable;

/**
 * Class PluginsTableTest
 *
 * @package Extensions\Test\TestCase\Model\Table
 */
class PluginsTableTest extends TestCase
{

    public $fixtures = ['plugin.extensions.plugins'];
    protected $_plugin = 'Core';
    protected $_corePlugin = 'Extensions';

    /**
     * @var PluginsTable
     */
    protected $Plugin;

    public function setUp()
    {
        parent::setUp();
        $this->Plugin = TableRegistry::get($this->_corePlugin . '.Plugins');
    }

    public function testClassName()
    {
        self::assertInstanceOf('Extensions\Model\Table\PluginsTable', $this->Plugin);
        self::assertSame(CMS_TABLE_PLUGINS, $this->Plugin->getTable());
        self::assertSame('id', $this->Plugin->getPrimaryKey());
        self::assertSame('Extensions\Model\Entity\Plugin', $this->Plugin->getEntityClass());
    }

    public function testValidationName()
    {
        $entity = $this->Plugin->newEntity(['name' => '']);
        $result = $this->Plugin->save($entity);

        self::assertFalse($result);
        self::assertSame([
            '_empty' => __d('extensions', 'Plugin name could not be empty.')
        ], $entity->getError('name'));
    }

    public function testValidationSlug()
    {
        $data = [
            'name'  => 'Simple',
            'slug'  => '',
            'core'  => false,
            'status' => true,
            'params' => []
        ];

        $entity = $this->Plugin->newEntity($data);
        $result = $this->Plugin->save($entity);

        self::assertFalse($result);
        self::assertSame([
            '_empty' => __d('extensions', 'Plugin slug could not be empty.')
        ], $entity->getError('slug'));

        $data['slug'] = 'community';
        $entity = $this->Plugin->newEntity($data);
        $result = $this->Plugin->save($entity);

        self::assertFalse($result);
        self::assertSame([
            'unique' => __d('extensions', 'Plugin with this slug already exists.')
        ], $entity->getError('slug'));

        $data['slug'] = 'simple';
        $entity = $this->Plugin->newEntity($data);
        $result = $this->Plugin->save($entity);

        self::assertSame([], $entity->getErrors());
        self::assertInstanceOf('Extensions\Model\Entity\Plugin', $result);
    }
}
