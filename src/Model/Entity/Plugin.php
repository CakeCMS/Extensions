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

namespace Extensions\Model\Entity;

use Cake\Utility\Inflector;
use Core\ORM\Entity\Entity;

/**
 * Class Plugin
 *
 * @package Extensions\Model\Table
 * @property string $name
 * @property string $slug
 * @property bool $core
 * @property bool $status
 */
class Plugin extends Entity
{

    /**
     * Get manifest data by key.
     *
     * @param string $key
     * @return \JBZoo\Data\Data
     */
    public function getManifestData($key = 'meta')
    {
        return \Core\Plugin::getData($this->getName(), $key);
    }

    /**
     * Get manifest meta data.
     *
     * @return \JBZoo\Data\Data
     */
    public function getMeta()
    {
        return $this->getManifestData();
    }

    /**
     * Camilize plugin name.
     *
     * @return string
     */
    public function getName()
    {
        return Inflector::camelize($this->slug);
    }
}
