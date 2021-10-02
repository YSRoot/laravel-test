## О репозитории
Тестовый репозиторий

## Installation

1. Скопировать .env.example в .env
2. docker-compose build && docker-compose up (docker-compose up --build)
3. Внутри контейнера выполнить php-artisan key:generate
4. Внутри контейнера выполнить php-artisan migrate --seed
5. Внутри контейнера выполнить php-artisan passport:install --uuids && php artisan passport:keys

Docker поднимет nginx/php-fpm/mysql. Сайт будет доступен по умолчанию на 8000 порту (можно изменить в .env APP_PORT=9000)

## Docs
Документация лежит в директории docs/swagger.yaml

### Social auth
Для работы социальной авторизации (google) нужно в .env вписать 
`GOOGLE_CLIENT_ID`
`GOOGLE_CLIENT_SECRET`
