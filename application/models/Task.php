<?php

namespace application\models;

use core\ActiveRecord;
/**
 * This is the model class for table "task"
 * Used magic get and set method in ActiveRecord pattern
 *
 * @property int $id
 * @property string|null $text
 * @property string|null $username
 * @property string|null $email
 * @property int $status
 */
class Task extends ActiveRecord
{
    protected $errorData = [];

    public function loadData($data)
    {
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->text = (isset($data['text'])) ? $data['text'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
    }

    public function validate()
    {
        $error = true;
        if (empty($this->getValue('text'))) {
            $this->errorData['text'] = 'Обязательно добавьте текст задачи';
            $error = false;
        }
        if (empty($this->getValue('username'))) {
            $this->errorData['username'] = 'Обязательно введите имя';
            $error = false;
        }
        if (empty($this->getValue('email'))) {
            $this->errorData['email'] = 'Обязательно введите email';
            //todo валидация по маске почты
            $error = false;
        }

        return $error;
    }

    public function getError($name)
    {
        if (!empty($this->errorData[$name])) {
            return $this->errorData[$name];
        }
    }

    protected static function getTableName(): string
    {
        return 'task';
    }
}