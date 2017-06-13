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

namespace Extensions\Model\Table;

use Cake\ORM\Query;
use Core\ORM\Table;
use Cake\Validation\Validator;

/**
 * Class PluginsTable
 *
 * @package Extensions\Model\Table
 * @method Query findBySlug($alias)
 * @method filterParams(array $query)
 */
class PluginsTable extends Table
{

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config
     * @return void
     * @throws \RuntimeException
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this
            ->setTable(CMS_TABLE_PLUGINS)
            ->setPrimaryKey('id')
            ->addBehavior('Search.Search');
    }

    /**
     * Returns the default validator object.
     *
     * @param Validator $validator
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('name', __d('extensions', 'Plugin name could not be empty.'));

        $validator
            ->notEmpty('slug', __d('extensions', 'Plugin slug could not be empty.'))
            ->add('slug', 'unique', [
                'message'  => __d('extensions', 'Plugin with this slug already exists.'),
                'rule'     => 'validateUnique',
                'provider' => 'table'
            ]);

        return $validator;
    }
}
