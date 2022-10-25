<?php
/* @var $model \application\models\Task */
/* @var $urlForm string */
/* @var $action string */
?>
<?php

?>
<form action="<?= $urlForm ?>" method="post" id="formModal">
    <div class="form-group">
        <label>Имя</label>
        <input class="form-control" type="text" name="username" value="<?= $model->username ?>">
        <div class="help-block"><?= $model->getError('username') ?></div>

    </div>
    <div class="form-group">
        <label>Email</label>
        <input class="form-control" type="text" name="email" value="<?= $model->email ?>">
        <div class="help-block"><?= $model->getError('email') ?></div>
    </div>
    <div class="form-group">
        <label>Текст</label>
        <textarea class="form-control" rows="3" name="text"><?= $model->text ?></textarea>
        <div class="help-block"><?= $model->getError('text') ?></div>
    </div>
    <?php if ($action == 'Edit') : ?>
        <div class="form-group">
            <label>Статус</label>
            <div class="form-check">
                <input name="status" type="radio" value="0" id="status0" <?= ($model->status == 0) ? 'checked' : '' ?>>
                <label class="form-check-label" for="status0">
                    Не проверено
                </label>
            </div>
            <div class="form-check">
                <input name="status" type="radio" value="1" id="status1"  <?= ($model->status == 1) ? 'checked' : '' ?>>
                <label class="form-check-label" for="status1">
                    Выполнено
                </label>
            </div>
        </div>
    <?php endif; ?>
    <button type="submit" class="btn btn-primary btn-block">Сохранить</button>
</form>