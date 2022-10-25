<?php


namespace application\models;


class User
{
    //костыльный способ проверки работы юзеров и авторизаций
    //todo сделать через кеши и БД
    private static $dataset = [
        [
            'id'=>2134,
            'login' => 'admin',
            'password' => '123',
            'role' => 'admin',
        ],
        [
            'id'=>345345,
            'login' => 'user',
            'password' => '123',
            'role' => 'user',
        ],
    ];
    public static function authenticate($data)
    {
        if ((empty($data['username']))||(empty($data['password']))){
            return false;
        }
        foreach (static::$dataset as $item){
            if (($item['login'] == $data['username'])&&($item['password'] ==  $data['password'])){
                $_SESSION['authenticate_user'] = $item['id'];
                return true;
            }
        }
        return false;
    }
    public static function getName()
    {
        if (empty( $_SESSION['authenticate_user'] ))
            return false;
        foreach (static::$dataset as $item){
            if ($item['id'] == $_SESSION['authenticate_user']){
                return $item['login'];
            }
        }
    }
    public static function isRole($role)
    {
        if (empty( $_SESSION['authenticate_user'] ))
            return false;
        foreach (static::$dataset as $item){
            if ($item['id'] == $_SESSION['authenticate_user']){
                if ($role == $item['role'])
                    return true;
                break;
            }
        }
        return false;
    }
}