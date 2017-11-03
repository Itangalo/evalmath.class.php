all: test

test: phpunit.phar
	./phpunit.phar --bootstrap expression.php tests/*

phpunit.phar:
	wget --no-check-certificate https://phar.phpunit.de/phpunit.phar -O phpunit.phar
	chmod +x phpunit.phar

 