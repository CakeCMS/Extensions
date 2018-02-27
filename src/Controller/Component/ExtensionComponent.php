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

namespace Extensions\Controller\Component;

use Core\Plugin;
use JBZoo\Utils\Str;
use Cake\Utility\Inflector;
use Core\Controller\Component\AppComponent;
use Extensions\Controller\Admin\PluginsController;

/**
 * Class ExtensionComponent
 *
 * @package Extensions\Controller\Component
 */
class ExtensionComponent extends AppComponent
{

    /**
     * Controller object.
     *
     * @var PluginsController
     */
    protected $_controller;

    /**
     * Get current plugin entity.
     *
     * @param   string $plugin
     * @return  \Cake\Datasource\EntityInterface|\Cake\ORM\Entity|mixed
     */
    public function getEntity($plugin)
    {
        $plugin = Inflector::camelize($plugin);
        $slug   = Str::low($plugin);
        $entity = $this->_controller->Extensions->findBySlug($slug)->first();
        if ($entity === null) {
            $entity = $this->_controller->Extensions->newEntity([
                'params' => [],
                'slug'   => $slug,
                'name'   => $plugin
            ]);

            $config = Plugin::getData($plugin, 'meta');
            $entity
                ->set('core', $config->get('core', false))
                ->set('name', $config->get('name', $plugin));
        }

        return $entity;
    }
}
