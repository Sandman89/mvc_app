**MVC приложение**

Роуты настраиваются в application/config/routes
Коннект к бд в application/config/db

Модели наследуют паттерн ActiveRecord с магическими методами
сет и гет свойств модели

пример:

**Create new Entity:**
```php
$entity = new Entity();
$entity->text = 'hello world';
$entity->save();
```

На frontend используется bootstrap библиотеки
