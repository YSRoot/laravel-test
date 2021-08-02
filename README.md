## О репозитории
Тестовый репозиторий

## Installation

1. Скопировать .env.example в .env
2. Проставить GITHUB_API_TOKEN в .ENV
3. docker-compose build && docker-compose up (docker-compose up --build)
4. Внутри контейнера выполнить php-artisan key:generate

Docker поднимет nginx/php-fpm/mysql. Сайт будет доступен по умолчанию на 8000 порту (можно изменить в .env APP_PORT=9000)
