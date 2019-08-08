phpunit:
	./vendor/bin/simple-phpunit -v

phpunit-code-coverage-html:
	./vendor/bin/simple-phpunit --coverage-html code-coverage-result/

phpunit-code-coverage-text:
	./vendor/bin/simple-phpunit --coverage-text

cs-fixer:
	./vendor/bin/php-cs-fixer fix src --rules=@Symfony,-@PSR1,-@PSR2
	./vendor/bin/php-cs-fixer fix tests --rules=@Symfony,-@PSR1,-@PSR2
