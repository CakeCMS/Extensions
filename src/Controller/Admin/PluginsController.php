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
use Extensions\Migration\Migration;
use Extensions\Model\Entity\Extension;
use Extensions\Model\Table\ExtensionsTable;
use Cake\View\Exception\MissingViewException;
use Cake\Core\Exception\MissingPluginException;
use Cake\Datasource\Exception\MissingModelException;
use Extensions\Controller\Component\ExtensionComponent;
use Cake\ORM\Exception\RolledbackTransactionException;

/**
 * Class PluginsController
 *
 * @package     Extensions\Controller\Admin
 * @property    ExtensionComponent $Extension
 * @property    ExtensionsTable $Extensions
 */
class PluginsController extends AppController
{

    /**
     * Config save/update action.
     *
     * @param   null|string $alias
     * @return  \Cake\Http\Response|null
     *
     * @throws  MissingViewException
     * @throws  MissingPluginException
     * @throws  \InvalidArgumentException
     * @throws  RolledbackTransactionException
     */
    public function config($alias = null)
    {
        $alias  = Str::low($alias);
        $plugin = Inflector::camelize($alias);
        if (Plugin::loaded($plugin)) {
            $entity = $this->Extension->getEntity($plugin);
            if ($this->request->is(['post', 'put'])) {
                $entity = $this->Extensions->patchEntity($entity, $this->request->getData());
                $result = $this->Extensions->save($entity);
                if ($result) {
                    $this->Flash->success(__d('extensions', 'The plugin settings has been saved.'));
                    return $this->App->redirect([
                        'apply' => ['action' => 'config', $result->get('slug')]
                    ]);
                }

                $this->Flash->error(__d('extensions', 'The settings could not be saved. Please, try again.'));
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
     * @return  void
     *
     * @throws  \RuntimeException
     */
    public function index()
    {
        $query = $this->Extensions->find('search', $this->Extensions->filterParams($this->request->getQueryParams()));

        $this
            ->set('plugins', $this->paginate($query))
            ->set('page_title', __d('extensions', 'The list of available plugins'));
    }

    /**
     * Initialization hook method.
     *
     * @return  void
     *
     * @throws  MissingModelException
     * @throws  \InvalidArgumentException
     * @throws  \UnexpectedValueException
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent($this->plugin . '.Extension');
        $this->loadModel($this->plugin . '.Extensions');
    }

    /**
     * Migrate plugin action.
     *
     * @param   string|null $pluginName
     * @return  \Cake\Http\Response|null
     *
     * @throws  \InvalidArgumentException
     * @throws  \Cake\Core\Exception\MissingPluginException
     */
    public function migrate($pluginName = null)
    {
        $slug = Inflector::underscore($pluginName);
        /** @var Extension $plugin */
        $plugin = $this->Extensions->findBySlug($slug)->first();

        $redirectAction = ['action' => 'index'];
        if ($plugin !== null && Plugin::loaded($plugin->name)) {
            $migration  = new Migration($plugin->name);
            $migrations = $migration->getData();
            $pluginDomainName = sprintf('<strong>%s</strong>', __d($plugin->slug, $plugin->name));

            if (count($migrations) <= 0) {
                $this->Flash->error(__d('extensions', 'Not found migration for «{0}»', $pluginDomainName));
                return $this->redirect($redirectAction);
            }

            try {
                $manager = $migration->getManager();
                $result  = $manager->migrateUp();

                if (count($result)) {
                    $this->Flash->success(__(implode('<br />', $result)));
                    return $this->redirect($redirectAction);
                }

                $this->Flash->success(__d('extensions', 'Something went wrong. try later'));
                return $this->redirect($redirectAction);
            } catch (\PDOException $e) {
                $this->Flash->error(__d('extensions', $e->getMessage()));
                return $this->redirect($redirectAction);
            }
        }

        $this->Flash->error(__d('extensions', 'Not found «{0}» plugin', $pluginName));
        return $this->redirect($redirectAction);
    }

    /**
     * Toggle action.
     *
     * @param   int $id
     * @param   $status
     *
     * @return  void
     */
    public function toggle($id, $status)
    {
        $this->App->toggleField($this->Extensions, $id, $status);
    }
}
