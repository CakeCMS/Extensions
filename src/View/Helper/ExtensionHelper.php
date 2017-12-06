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

namespace Extensions\View\Helper;

use Core\Plugin;
use JBZoo\Data\Data;
use JBZoo\Utils\Str;
use Core\View\Helper\AppHelper;
use Extensions\Model\Entity\Extension;
use Cake\View\Exception\MissingElementException;

/**
 * Class ExtensionHelper
 *
 * @package Extensions\View\Helper
 */
class ExtensionHelper extends AppHelper
{

    /**
     * Render plugin params.
     *
     * @return string
     * @throws MissingElementException
     */
    public function renderPluginParams()
    {
        /** @var Extension $entity */
        $tabNav     = [];
        $tabContent = [];
        $entity     = $this->_View->get('entity');
        $params     = Plugin::getData($entity->getName(), 'params');
        foreach ($params->getArrayCopy() as $title => $_params) {
            $fields   = [];
            $_params  = new Data($_params);
            $tabId    = 'tab-' . Str::slug($title);
            $tabNav[] = $this->_View->element('Extensions.Params/tab', [
                'title'  => $title,
                'params' => $_params,
                'tabId'  => $tabId
            ]);

            foreach ($_params as $key => $options) {
                $fieldName = Str::clean('params.' . $key);
                if (is_callable($options)) {
                    $fields[] = call_user_func($options, $this->_View);
                    continue;
                }
                
                if (isset($options['options']) && is_callable($options['options'])) {
                    $options['options'] = call_user_func($options['options'], $this->_View);
                }

                $fields[] = $this->_View->Form->control($fieldName, $options);
            }

            $tabContent[$tabId] = implode(PHP_EOL, $fields);
        }

        return implode(PHP_EOL, [
            $this->_View->element('Extensions.Params/list', ['items' => $tabNav]),
            $this->_View->element('Extensions.Params/content', ['content' => $tabContent])
        ]);
    }
}
