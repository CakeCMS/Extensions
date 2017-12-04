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

namespace Extensions\Test\TestCase\View\Helper;

use Core\Plugin;
use Test\Cases\TestCase;
use Test\App\View\AppView;
use Cake\Http\ServerRequest;
use Extensions\View\Helper\ExtensionHelper;
use Extensions\Model\Entity\Plugin as PluginEntity;

/**
 * Class ExtensionHelperTest
 *
 * @package Extensions\Test\TestCase\View\Helper
 */
class ExtensionHelperTest extends TestCase
{

    protected $_corePlugin = 'Extensions';

    /**
     * @var ExtensionHelper
     */
    protected $_helper;

    public function setUp()
    {
        parent::setUp();

        $request = new ServerRequest([
            'params' => [
                'prefix' => 'admin',
                'pass'  => []
            ]
        ]);

        $view = new AppView($request);
        $view->set('entity', new PluginEntity([
            'name' => 'Tester',
            'slug' => 'tester'
        ]));

        $this->_helper = new ExtensionHelper($view);
        Plugin::load('Tester', ['autoload' => true]);
    }

    public function tearDown()
    {
        parent::tearDown();
        Plugin::unload('Tester');
    }

    public function testRenderPluginParams()
    {
        $output = $this->_helper->renderPluginParams();

        $expected = [
            'ul' => ['class' => 'tabs'],
                ['li' => ['class' => 'tab']],
                    ['a' => [
                        'href'  => '#tab-messages',
                        'title' => 'Messages',
                        'class' => 'ck-link'
                    ]],
                        ['span' => ['class' => 'ck-link-title']],
                            'Messages',
                        '/span',
                    '/a',
                '/li',
            '/ul',
            ['div' => ['id' => 'tab-messages']],
                ['div' => ['class' => 'input text']],
                    ['label' => ['for' => 'params-msg-account-create-subject']],
                        'Message subject',
                    '/label',
                    ['input' => [
                        'type'  => 'text',
                        'name'  => 'params[msg_account_create_subject]',
                        'id'    => 'params-msg-account-create-subject',
                        'value' => 'Account activation'
                    ]],
                '/div',
                'From callable',
                ['div' => ['class' => 'input select']],
                    ['label' => ['for' => 'params-test']],
                        'Test',
                    '/label',
                    'select' => ['name' => 'params[test]', 'id' => 'params-test'],
                        ['option' => ['value' => 0]], 1, '/option',
                        ['option' => ['value' => 1]], 2, '/option',
                        ['option' => ['value' => 2]], 7, '/option',
                    '/select',
                '/div',
            '/div'
        ];

        $this->assertHtml($expected, $output);
    }
}