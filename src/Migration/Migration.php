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

namespace Extensions\Migration;

use Core\Plugin;
use JBZoo\Utils\FS;
use Cake\Filesystem\Folder;
use Cake\Utility\Inflector;

/**
 * Class Migration
 *
 * @package Extensions\Migration
 */
class Migration
{

    const MIGRATION_DIR = 'Migrations';

    /**
     * Plugin name.
     *
     * @var null|string
     */
    protected $_plugin;

    /**
     * Migration constructor.
     *
     * @param $plugin
     */
    public function __construct($plugin)
    {
        $this->_plugin = $plugin;
    }

    /**
     * Get data for migration.
     *
     * @return array
     *
     * @throws \Cake\Core\Exception\MissingPluginException
     */
    public function getData()
    {
        $data   = [];
        $path   = $this->getPath();
        $dir    = new Folder($path);
        $files  = (array) $dir->find('.*\.php');

        if (count($files) > 0) {
            foreach ($files as $file) {
                $name     = FS::filename($file);
                $segments = explode('_', $name);
                $version  = array_shift($segments);
                $class    = Inflector::camelize(implode('_', $segments));

                $data[$version] = [
                    'class' => $class,
                    'path'  => $path . DS . $file
                ];
            }
        }

        return $data;
    }

    /**
     * Get migration manager.
     *
     * @return Manager
     *
     * @throws \InvalidArgumentException
     * @throws \Cake\Core\Exception\MissingPluginException
     */
    public function getManager()
    {
        return new Manager($this->_plugin);
    }

    /**
     * Get plugin migration path.
     *
     * @return string
     *
     * @throws \Cake\Core\Exception\MissingPluginException
     */
    public function getPath()
    {
        return FS::clean(Plugin::path($this->_plugin) . '/config/' . self::MIGRATION_DIR, '/');
    }
}
