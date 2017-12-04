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

namespace Extensions\Test\TestCase\Model\Table;

use Test\Cases\TestCase;
use Cake\ORM\TableRegistry;
use Extensions\Model\Table\ExtensionsTable;

/**
 * Class ExtensionsTableTest
 *
 * @package Extensions\Test\TestCase\Model\Table
 */
class ExtensionsTableTest extends TestCase
{

    public $fixtures = ['plugin.extensions.extensions'];
    protected $_plugin = 'Core';
    protected $_corePlugin = 'Extensions';

    /**
     * @var ExtensionsTable
     */
    protected $Table;

    public function setUp()
    {
        parent::setUp();
        $this->Table = TableRegistry::get($this->_corePlugin . '.Extensions');
    }

    public function testClassName()
    {
        self::assertInstanceOf('Extensions\Model\Table\ExtensionsTable', $this->Table);
        self::assertSame(CMS_TABLE_EXTENSIONS, $this->Table->getTable());
        self::assertSame('id', $this->Table->getPrimaryKey());
        self::assertSame('Extensions\Model\Entity\Extension', $this->Table->getEntityClass());
    }

    public function testValidationName()
    {
        $entity = $this->Table->newEntity(['name' => '']);
        $result = $this->Table->save($entity);

        self::assertFalse((bool) $result);
        self::assertSame([
            '_empty' => __d('extensions', 'Extension name could not be empty.')
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

        $entity = $this->Table->newEntity($data);
        $result = $this->Table->save($entity);

        self::assertFalse((bool) $result);
        self::assertSame([
            '_empty' => __d('extensions', 'Extension slug could not be empty.')
        ], $entity->getError('slug'));

        $data['slug'] = 'community';
        $entity = $this->Table->newEntity($data);
        $result = $this->Table->save($entity);

        self::assertFalse((bool) $result);
        self::assertSame([
            'unique' => __d('extensions', 'Extension with this slug already exists.')
        ], $entity->getError('slug'));

        $data['slug'] = 'simple';
        $entity = $this->Table->newEntity($data);
        $result = $this->Table->save($entity);

        self::assertSame([], $entity->getErrors());
        self::assertInstanceOf('Extensions\Model\Entity\Extension', $result);
    }
}
