**MVC приложение**

Роуты настраиваются в application/config/routes
<br/>
Коннект к бд в application/config/db
<br/>
Модели наследуют паттерн ActiveRecord с магическими методами
сет и гет свойств модели
<br/>
пример:
**Create new Entity:**
```php
$entity = new Entity();
$entity->text = 'hello world';
$entity->save();
```
<br/>
На frontend используется bootstrap библиотеки
