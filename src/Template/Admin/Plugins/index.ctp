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
 * @var         \Extensions\Model\Entity\Extension $plugin
 */

use Core\Plugin;
use JBZoo\Utils\Str;
use Core\Toolbar\ToolbarHelper;
use Extensions\Migration\Manager as MigrateManager;

ToolbarHelper::delete();

$this->Assets->toggleField();
?>
<table class="ckTableProcess striped highlight responsive-table jsProcessTable jsToggleField">
    <?php
    $tHeaders = $this->Html->tableHeaders([
        [$this->Form->checkAll() => ['class' => 'center ck-hide-label', 'width' => '60px']],
        [$this->Paginator->sort('name') => ['width' => '70%']],
        ['' => ['width' => '60px']],
        [$this->Paginator->sort('status') => ['class' => 'center', 'width' => '60px']],
        [__d('extensions', 'Version') => ['class' => 'center']],
        [__d('extensions', 'Author') => ['class' => 'center']]
    ]);

    echo $this->Html->tag('thead', $tHeaders);

    $rows = [];
    foreach ($this->get('plugins') as $plugin) {
        $editLink = $this->Html->link($plugin->name, ['action' => 'config', $plugin->slug]);
        $data     = Plugin::getData($plugin->name, 'meta');

        $migrationLink = null;
        $migrate = new MigrateManager($plugin->name);

        if ($migrate->hasMigration()) {
            $migrationLink = $this->Html->link(null, [
                'controller' => 'plugins',
                'action'     => 'migrate',
                Str::low($plugin->name)
            ], [
                'icon'    => 'retweet',
                'class'   => 'btn btn-floating pulse',
                'tooltip' => __d('extensions', 'Plugin has new migrations')
            ]);
        }

        $rows[] = [
            [$this->Form->processCheck('user', $plugin->id), ['class' => 'center ck-hide-label']],
            $editLink,
            [$migrationLink, ['class' => 'center']],
            [$this->Html->toggle($plugin), ['class' => 'center']],
            [$data->get('version'), ['class' => 'center']],
            [$data->get('author'), ['class' => 'center']]
        ];
    }
    echo $this->Html->tableCells($rows);
    ?>
</table>
