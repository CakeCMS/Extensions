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
 * @var         \Extensions\Model\Entity\Extension $extension
 * @var         \Core\View\AppView $this
 */

use Core\Toolbar\ToolbarHelper;

ToolbarHelper::apply();
ToolbarHelper::save();
ToolbarHelper::cancel();

$extension = $this->get('entity');
$meta = $extension->getMeta();

echo $this->Form->create($extension, ['jsForm' => true]);
echo $this->Form->control('id');
?>
<div class="page-header">
    <h1 class="title"><?= $this->get('page_title') ?></h1>
</div>
<div class="row">
    <div class="col s8">
        <?= $this->Extension->renderPluginParams(); ?>
    </div>
    <div class="col s4">
        <?php
        if (!$meta->get('core', false)) {
            echo $this->Form->switcher('status', [
                'title' => __d('extension', 'Plugin status')
            ]);
        }
        ?>
        <ul class="collection with-header">
            <li class="collection-item">
                <?= __d('extensions', 'Plugin name: {0}', __d($extension->slug, $meta->get('name'))) ?>
            </li>
            <li class="collection-item">
                <?= __d('extensions', 'Author: {0}', $meta->get('author')) ?>
            </li>
            <li class="collection-item">
                <?= __d('extensions', 'Version: {0}', $meta->get('version')) ?>
            </li>
            <li class="collection-item">
                <?= __d('extensions', 'Description: {0}', $meta->get('description')) ?>
            </li>
            <?php if ($meta->get('copyright') !== null) : ?>
                <li class="collection-item">
                    <?= __d('extensions', 'Copyright: {0}', $meta->get('copyright')) ?>
                </li>
            <?php endif; ?>
            <?php if ($meta->get('license') !== null) : ?>
                <li class="collection-item">
                    <?= __d('extensions', 'License: {0}', $meta->get('license')) ?>
                </li>
            <?php endif; ?>
            <?php if ($meta->get('url') !== null) : ?>
                <li class="collection-item">
                    <?= __d('extensions', 'Author url: {0}', $this->Html->link($meta->get('url'), $meta->get('url'))) ?>
                </li>
            <?php endif; ?>
            <?php if ($meta->get('email') !== null) : ?>
                <li class="collection-item">
                    <?= __d(
                        'extensions',
                        'Author email: {0}', $this->Html->link($meta->get('email'), 'mailto:' . $meta->get('email')))
                    ?>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<?php echo $this->Form->end();
