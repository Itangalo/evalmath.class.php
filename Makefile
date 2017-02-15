
all: phpunit.phar


phpunit.phar:
	wget --no-check-certificate https://phar.phpunit.de/phpunit.phar -O phpunit.phar
	chmod +x phpunit.phar

test:
	./phpunit.phar --bootstrap expression.php tests/Expression.php

 