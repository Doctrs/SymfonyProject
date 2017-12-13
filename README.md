Article Symfony
=======

Тестовое задание.
Полное задание - https://docs.google.com/document/d/1At_k0rraptj2eLKqKg5cpopFBmtDZqOcWzPs3UVVorM/edit#heading=h.ohuxsci5gden

Установка
===

```php
composer install
php bin/console doctrine:migrations:migrate
```

В случае с mysql 5.7 потребуется установить
```
sql-mode=""
```
в файле конфигурации
```
/etc/mysql/my.cnf
```
Потому что фиксы для модов в symfony3.4 еще не готовы

Тесты
```
./vendor/bin/simple-phpunit
```