################################################################################
################################################################################

# PROXIES (to Docker-containers commands)
alias php="docker-compose run --rm php-cli"
alias yarn="docker-compose run --rm frontend-node-cli yarn"
alias node-cli="docker-compose run --rm frontend-node-cli"
alias composer="docker-compose run --rm php-cli composer"
alias symfony="docker-compose run --rm php-cli symfony"
alias app="docker-compose run --rm php-cli php bin/app.php --ansi --no-interaction"
alias linter="docker-compose run --rm php-cli composer lint"
alias test-e2e="api-fixtures && cucumber-clear && cucumber-e2e"
alias api-test="docker-compose run --rm php-cli composer test"
alias phpunit="docker-compose run --rm php-cli php ./bin/phpunit --exclude-group ignore"

# Symfony
alias console="docker-compose run --rm php-cli php ./bin/console"