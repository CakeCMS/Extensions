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

namespace Extensions\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Class PhinxlogFixture
 *
 * @package Extensions\Test\Fixture
 */
class PhinxlogFixture extends TestFixture
{

    /**
     * Full Table Name
     *
     * @var string
     */
    public $table = 'phinxlog';

    /**
     * Fields of property.
     *
     * @var array
     */
    public $fields = [
        'version'           => ['type' => 'integer'],
        'migration_name'    => ['type' => 'datetime'],
        'start_time'        => ['type' => 'datetime'],
        'end_time'          => ['type' => 'integer'],
        'breakpoint'        => ['type' => 'integer'],
        '_constraints'      => []
    ];

    /**
     * Initialize the fixture.
     *
     * @return void
     * @throws \Cake\ORM\Exception\MissingTableClassException When importing from a table that does not exist.
     */
    public function init()
    {
        $this->records = [];

        parent::init();
    }
}
