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

$this->Document->meta([
    '<title>' . $this->fetch('page_title') . '</title>',
    '<meta http-equiv="X-UA-Compatible" content="IE=edge">',
    '<meta name="viewport" content="width=device-width, initial-scale=1">',
], 'meta');

echo $this->Document->type();
?>
<head>
    <?= $this->Document->head() ?>
</head>
<body>
<div class="content">
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
</div>
<?= $this->Document->assets('script') ?>
<?= $this->fetch('script') ?>
<?= $this->fetch('script_bottom') ?>
</body>
</html>