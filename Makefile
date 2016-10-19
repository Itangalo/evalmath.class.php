

all: phpunit.phar


phpunit.phar:
	wget https://phar.phpunit.de/phpunit.phar
	chmod +x phpunit.phar

test:
	./phpunit.phar --bootstrap expression.php tests/Expression

