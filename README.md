RUN PROJECT:

```bash
composer install

docker-compose up -d

docker-compose exec novex-php php /app/bin/console doctrine:migrations:migrate --no-interaction
```

1) Показать пользователей: http://localhost/api/user/ [GET]
2) Добавить пользователя http://localhost/api/user [POST] {string "email", string "name", int "age", string "sex", string "birthday", string "birthday", string "phone"}
3) Показать пользователя http://localhost/api/user/{id} [GET] int $id
4) Редактировать пользователя http://localhost/api/user/{id} [POST] int $id {любые поля которые нужно изменить в БД, например:string "name",int "age"}
5) Удаление пользователя http://localhost/api/user/{id} [DELETE] int $id
