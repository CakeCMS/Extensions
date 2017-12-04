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

use Core\Nav;

Nav::add('sidebar', 'extensions', [
    'title' =>__d('extensions', 'Extensions'),
    'weight'=> 100,
    'icon' => 'puzzle-piece',
    'url' => '#',
    'children' => [
        'plugins' => [
            'title' => __d('extensions', 'Plugins'),
            'weight' => 10,
            'url' => [
                'plugin' => 'Extensions',
                'controller' => 'Plugins',
                'action' => 'index'
            ]
        ]
    ]
]);
