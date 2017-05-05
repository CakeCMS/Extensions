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

namespace Extensions\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Class PluginsFixture
 *
 * @package Extensions\Model\Test\Fixture
 */
class PluginsFixture extends TestFixture
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
        'core'         => ['type' => 'integer'],
        'status'       => ['type' => 'integer'],
        'params'       => 'text',
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
    ];

    /**
     * Records property.
     *
     * @var array
     */
    public $records = [
        [
            'id'     => 1,
            'name'   => 'Community',
            'slug'   => 'community',
            'core'   => 1,
            'status' => 1,
            'params' => '{
                "max_auth": "5",
                "default_role": "1",
                "msg_account_create": "54",
                "msg_activate": "76\u0440\u0430\u043f",
                "msg_forgot": "",
                "msg_adm_new_registration": ""
            }'
        ],
        [
            'id'     => 2,
            'name'   => 'Custom',
            'slug'   => 'custom',
            'core'   => 0,
            'status' => 1,
            'params' => '{
                "param-1": "val-1",
                "param-2": "val-2"
            }'
        ],
        [
            'id'     => 3,
            'name'   => 'TestPlugin',
            'slug'   => 'test_plugin',
            'core'   => 1,
            'status' => 1,
            'params' => '{
                "param-1": "val-1",
                "param-2": "val-2"
            }'
        ]
    ];
}
