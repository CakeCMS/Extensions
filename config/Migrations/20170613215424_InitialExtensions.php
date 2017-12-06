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

use Migrations\AbstractMigration;

/**
 * Class InitialExtensions
 */
class InitialExtensions extends AbstractMigration
{

    /**
     * Whether the tables created in this migration
     * should auto-create an `id` field or not
     *
     * This option is global for all tables created in the migration file.
     * If you set it to false, you have to manually add the primary keys for your
     * tables using the Migrations\Table::addPrimaryKey() method
     *
     * @var bool
     */
    public $autoId = false;

    /**
     * Migrate Up.
     *
     * @return void
     * @throws \RuntimeException|\InvalidArgumentException
     */
    public function up()
    {
        $this->table('extensions')
            ->addColumn('id', 'integer', [
                'limit'         => 11,
                'autoIncrement' => true,
                'default'       => null,
                'null'          => false,
                'signed'        => false
            ])
            ->addPrimaryKey([
                'id'
            ])
            ->addColumn('name', 'string', [
                'limit'   => 150,
                'default' => null,
                'null'    => false
            ])
            ->addColumn('type', 'string', [
                'limit'   => 20,
                'default' => null,
                'null'    => false
            ])
            ->addColumn('ordering', 'integer', [
                'default' => 0,
                'limit'   => 11,
                'null'    => false
            ])
            ->addColumn('slug', 'string', [
                'limit'   => 150,
                'default' => null,
                'null'    => false
            ])
            ->addColumn('core', 'boolean', [
                'limit'   => null,
                'default' => false,
                'null'    => false
            ])
            ->addColumn('status', 'boolean', [
                'limit'   => null,
                'default' => false,
                'null'    => false
            ])
            ->addColumn('params', 'text', [
                'default' => null,
                'limit'   => null,
                'null'    => true
            ])
            ->create();
    }

    /**
     * Migrate Down.
     *
     * @return void
     */
    public function down()
    {
        $this->dropTable('extensions');
    }
}
