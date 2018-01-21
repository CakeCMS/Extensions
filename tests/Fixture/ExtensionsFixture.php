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

namespace Extensions\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Class ExtensionsFixture
 *
 * @package Extensions\Test\Fixture
 */
class ExtensionsFixture extends TestFixture
{

    /**
     * Fields property.
     *
     * @var array
     */
    public $fields = [
        'id'           => ['type' => 'integer'],
        'name'         => ['type' => 'string'],
        'slug'         => ['type' => 'string'],
        'type'         => ['type' => 'string'],
        'ordering'     => ['type' => 'integer'],
        'core'         => ['type' => 'integer'],
        'status'       => ['type' => 'integer'],
        'params'       => 'text',
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
    ];

    /**
     * Initialize the fixture.
     *
     * @return void
     * @throws \Cake\ORM\Exception\MissingTableClassException When importing from a table that does not exist.
     */
    public function init()
    {
        $this->records = [
            [
                'id'       => 1,
                'name'     => 'Community',
                'slug'     => 'community',
                'type'     => 'plugin',
                'ordering' => 10,
                'core'     => 1,
                'status'   => 1,
                'params'   => json_encode([
                    'max_auth'                  => 5,
                    'default_role'              => 1,
                    'msg_account_create'        => 54,
                    'msg_adm_new_registration'  => '',
                    'msg_forgot'                => 'Forgot message',
                    'msg_activate'              => 'Activate message'
                ])
            ],
            [
                'id'       => 2,
                'name'     => 'Custom',
                'slug'     => 'custom',
                'type'     => 'plugin',
                'ordering' => 10,
                'core'     => 0,
                'status'   => 1,
                'params'   => json_encode([
                    'param-1' => 'val-1',
                    'param-2' => 'val-2'
                ])
            ],
            [
                'id'       => 3,
                'name'     => 'TestPlugin',
                'slug'     => 'test_plugin',
                'type'     => 'plugin',
                'ordering' => 10,
                'core'     => 1,
                'status'   => 1,
                'params'   => json_encode([
                    'param-1' => 'val-1',
                    'param-2' => 'val-2'
                ])
            ],
            [
                'id'       => 4,
                'name'     => 'Tester',
                'slug'     => 'tester',
                'type'     => 'plugin',
                'ordering' => 10,
                'core'     => 1,
                'status'   => 1,
                'params'   => json_encode([
                    'param-1' => 'val-1',
                    'param-2' => 'val-2'
                ])
            ],
            [
                'id'       => 5,
                'name'     => 'Clean',
                'slug'     => 'clean',
                'type'     => 'plugin',
                'ordering' => 10,
                'core'     => 1,
                'status'   => 1,
                'params'   => json_encode([
                    'param-1' => 'val-1',
                    'param-2' => 'val-2'
                ])
            ]
        ];

        parent::init();
    }
}
