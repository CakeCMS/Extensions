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

namespace Extensions;

use JBZoo\Utils\Arr;
use JBZoo\Utils\Str;
use Cake\ORM\TableRegistry;
use Core\ORM\Entity\Entity;
use Core\Plugin as CorePlugin;
use Cake\Core\Exception\MissingPluginException;

/**
 * Class Plugin
 *
 * @package Extensions
 */
class Plugin extends CorePlugin
{

    /**
     * List of plugin entity.
     *
     * @var array
     */
    protected static $_pluginsList = [];

    /**
     * Get plugin extension instance.
     *
     * @param   string $name Plugin name.
     * @return  Entity
     */
    public static function getInstance($name)
    {
        if (!self::loaded($name)) {
            throw new MissingPluginException(['plugin' => $name]);
        }

        if (!Arr::key($name, self::$_pluginsList)) {
            $slug  = Str::low($name);
            $table = TableRegistry::get('Extensions.Extensions');

            $entity = $table
                ->find()
                ->where([
                    'slug' => $slug,
                    'type' => EXT_TYPE_PLUGIN
                ])
                ->first();

            self::$_pluginsList[$name] = $entity;
        }

        return self::$_pluginsList[$name];
    }

    /**
     * Get plugin params.
     *
     * @param   string $name    Plugin name.
     * @return  \JBZoo\Data\Data
     */
    public static function getParams($name)
    {
        return self::getInstance($name)->params;
    }
}
