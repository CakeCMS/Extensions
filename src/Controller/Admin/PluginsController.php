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

namespace Extensions\Controller\Admin;

use JBZoo\Utils\Str;
use Cake\Core\Plugin;
use Cake\Utility\Inflector;
use Core\Migration\Migration;
use Extensions\Model\Table\PluginsTable;
use Cake\View\Exception\MissingViewException;
use Cake\Core\Exception\MissingPluginException;
use Extensions\Controller\Component\PluginComponent;
use Cake\ORM\Exception\RolledbackTransactionException;

/**
 * Class PluginsController
 *
 * @package Extensions\Controller\Admin
 * @property PluginsTable $Plugins
 * @property PluginComponent $Plugin
 */
class PluginsController extends AppController
{

    /**
     * Config save/update action.
     *
     * @param null|string $alias
     * @throws MissingPluginException
     * @throws MissingViewException
     * @throws RolledbackTransactionException
     * @throws \InvalidArgumentException
     * @return \Cake\Http\Response|null
     */
    public function config($alias = null)
    {
        $alias  = Str::low($alias);
        $plugin = Inflector::camelize($alias);
        if (Plugin::loaded($plugin)) {
            $entity = $this->Plugin->getEntity($plugin);
            if ($this->request->is(['post', 'put'])) {
                $entity = $this->Plugins->patchEntity($entity, $this->request->getData());
                $result = $this->Plugins->save($entity);
                if ($result) {
                    $this->Flash->success(__d('extensions', 'The plugin settings has been saved.'));
                    return $this->App->redirect([
                        'apply' => ['action' => 'config', $result->get('slug')]
                    ]);
                } else {
                    $this->Flash->error(__d('extensions', 'The settings could not be saved. Please, try again.'));
                }
            }
        } else {
            throw new MissingPluginException(['plugin' => $plugin]);
        }

        $this
            ->set('plugin', $plugin)
            ->set('entity', $entity)
            ->set('page_title', __d('extensions', 'Configuration plugin «{0}»', __d($alias, $plugin)));
    }

    /**
     * Index action.
     *
     * @return void
     * @throws \RuntimeException
     */
    public function index()
    {
        $query = $this->Plugins->find('search', $this->Plugins->filterParams($this->request->getQueryParams()));
        $this->set('plugins', $this->paginate($query));
        $this->set('page_title', __d('extensions', 'The list of available plugins'));
    }

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent($this->plugin . '.Plugin');
    }

    /**
     * Toggle action.
     *
     * @param int $id
     * @param $status
     */
    public function toggle($id, $status)
    {
        $this->App->toggleField($this->Plugins, $id, $status);
    }

    public function migrate($plugin = null)
    {
        $slug = Str::low($plugin);
        /** @var \Extensions\Model\Entity\Plugin|null $plugin */
        $plugin = $this->Plugins->findBySlug($slug)->first();

        if ($plugin !== null && Plugin::loaded($plugin->name)) {
            $pluginDomainName = sprintf('<strong>%s</strong>', __d($plugin->slug, $plugin->name));
            $migrations = Migration::getData($plugin->name);
            if (count($migrations) <= 0) {
                $this->Flash->error(__d('extensions', 'Not found migration for «{0}»', $pluginDomainName));
                return $this->redirect(['action' => 'index']);
            }

            $manager = Migration::getManager($plugin->name);
            dump($manager->migrateUp());
        }
    }
}
