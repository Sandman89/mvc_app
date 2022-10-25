<?php
/* @var $this \yii\web\View */
/* @var $content string */

use application\models\User;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->title; ?></title>
    <link href="/public/styles/main.css" rel="stylesheet">
    <link href="/public/styles/bootstap.css" rel="stylesheet">


    <script src="/public/scripts/jquery.js"></script>
    <script src="/public/scripts/popper.js"></script>
    <script src="/public/scripts/bootstrap.js"></script>
    <script src="/public/scripts/app.js"></script>
</head>
<body>
<div class="wrap">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item ">
                        <a class="nav-link" href="/">Главная</a>
                    </li>
                    <?php $name = User::getName();
                        if (empty($name)) :
                    ?>
                    <li class="nav-item ">
                        <a class="nav-link" href="/account/login">Вход</a>
                    </li>
                    <?php else: ?>
                            <li class="nav-item ">
                                <a class="nav-link" href="/account/logout">Выход (<?= $name ?>)</a>
                            </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

    </nav>
    <div class="container">
        <?php echo $content; ?>
    </div>
</div>
</body>
</html>