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
 * @var         \Core\View\AppView $this
 * @var         string $tabId
 * @var         \Extensions\Model\Entity\Plugin $plugin
 */

$href   = '#' . $tabId;
$plugin = $this->get('entity');
?>
<li class="tab">
    <?= $this->Html->link(__d($plugin->slug, $title), $href, ['title' => __d($plugin->slug, $title)]) ?>
</li>