<?php
/* @var $this core\View */
$this->title = 'Вход';
?>
<div class="text-center">
    <form class="form-signin" action="/account/login" method="post">
        <h1 class="h3 mb-3 font-weight-normal">Авторизация</h1>
        <div class="form-group">
            <input type="text" name="username" class="form-control" placeholder="Логин" required=""
                   autofocus="">
        </div>
        <div class="form-group">
            <input type="password"  name="password"  class="form-control" placeholder="Пароль" required="">
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Вход</button>
    </form>
</div>