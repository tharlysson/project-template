Rodar os testes  
php vendor/bin/codecept run --coverage --coverage-xml --coverage-html

Criar migrations
php app/migrations generate

Executar migrations
php app/migrations migrate -N