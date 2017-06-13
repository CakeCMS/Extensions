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
 * @var       \Core\View\AppView $this
 * @var       \Extensions\Model\Entity\Plugin $plugin
 */

$this->Assets->toggleField();
?>
<div class="page-header">
    <h1 class="title"><?= $this->get('page_title') ?></h1>
</div>
<table class="ckTableProcess striped highlight responsive-table jsProcessTable jsToggleField">
    <?php
    $tHeaders = $this->Html->tableHeaders([
        [$this->Form->checkAll() => ['class' => 'center ck-hide-label']],
        $this->Paginator->sort('name'),
        [$this->Paginator->sort('status') => ['class' => 'center']],
        [__d('core', 'Actions') => ['class' => 'center']]
    ]);

    echo $this->Html->tag('thead', $tHeaders);

    $rows = [];
    foreach ($this->get('plugins') as $plugin) {
        $editLink = $this->Html->link($plugin->name, ['action' => 'config', $plugin->slug]);

        $rows[] = [
            [$this->Form->processCheck('user', $plugin->id), ['class' => 'center ck-hide-label']],
            $editLink,
            [$this->Html->toggle($plugin), ['class' => 'center']],
            ''
        ];
    }
    echo $this->Html->tableCells($rows);
    ?>
</table>