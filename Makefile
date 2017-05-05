#
# CakeCMS Extensions
#
# This file is part of the of the simple cms based on CakePHP 3.
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @package   Extensions
# @license   MIT
# @copyright MIT License http://www.opensource.org/licenses/mit-license.php
# @link      https://github.com/CakeCMS/Extensions
#

.PHONY: build update test-all autoload test phpmd phpcs phpcpd phploc coveralls

test-all:
	@echo -e "\033[0;33m>>> \033[0;30;46m Run all tests \033[0m"
	@make update autoload test-unit phpmd phpcs phpcpd phploc

update:
	@echo -e "\033[0;33m>>> \033[0;30;46m Update project \033[0m"
	@composer update --no-interaction --optimize-autoloader
	@echo ""

autoload:
	@echo -e "\033[0;33m>>> \033[0;30;46m Composer autoload \033[0m"
	@composer dump-autoload --optimize --no-interaction
	@echo ""

test-unit:
	@echo -e "\033[0;33m>>> \033[0;30;46m Run unit-tests \033[0m"
	@php ./vendor/phpunit/phpunit/phpunit --configuration ./phpunit.xml.dist
	@echo ""

phpmd:
	@echo -e "\033[0;33m>>> \033[0;30;46m Check PHPmd \033[0m"
	@php ./vendor/phpmd/phpmd/src/bin/phpmd ./src text ./vendor/jbzoo/misc/phpmd/jbzoo.xml --verbose
	@echo ""

phpcs:
	@echo -e "\033[0;33m>>> \033[0;30;46m Check Code Style \033[0m"
	@php ./vendor/squizlabs/php_codesniffer/scripts/phpcs ./src   \
        --standard=./vendor/jbzoo/misc/phpcs/JBZoo/ruleset.xml    \
        --report=full
	@echo ""

phpcpd:
	@echo -e "\033[0;33m>>> \033[0;30;46m Check Copy&Paste \033[0m"
	@php ./vendor/sebastian/phpcpd/phpcpd ./src --verbose
	@echo ""

phploc:
	@echo -e "\033[0;33m>>> \033[0;30;46m Show statistic \033[0m"
	@php ./vendor/phploc/phploc/phploc ./src --verbose
	@echo ""

coveralls:
	@echo -e "\033[0;33m>>> \033[0;30;46m Send coverage to coveralls.io \033[0m"
	@php ./vendor/satooshi/php-coveralls/bin/coveralls --verbose
	@echo ""
