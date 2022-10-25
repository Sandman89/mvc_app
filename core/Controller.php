<?php

namespace core;

use application\models\User;
use core\View;

abstract class Controller {

    public $route;
    public $view;
  //  private $accessConroll;

    public function __construct($route) {
        $this->route = $route;
        $this->view = new View();
        $this->before();
        $this->checkControl($this->accessControl());
    }

    public function accessControl(){
        return [];
    }

    private function checkControl($listControl){
        foreach ($listControl as $item){
            if ($item['action'] == $this->route['action']){
                if (!empty($item['role'])){
                    if (!User::isRole($item['role']))
                        self::errorCode(403);
                }
            }
        }
    }


    public function before(){
        return true;
    }

    public function checkAcl() {
        $this->acl = require 'application/acl/'.$this->route['controller'].'.php';
        if ($this->isAcl('all')) {
            return true;
        }
        elseif (isset($_SESSION['authorize']['id']) and $this->isAcl('authorize')) {
            return true;
        }
        elseif (!isset($_SESSION['authorize']['id']) and $this->isAcl('guest')) {
            return true;
        }
        elseif (isset($_SESSION['admin']) and $this->isAcl('admin')) {
            return true;
        }
        return false;
    }

    public function isAcl($key) {
        return in_array($this->route['action'], $this->acl[$key]);
    }

    public function redirect($url)
    {
        header('location: ' . $url);
        exit;
    }
    public static function errorCode($code)
    {
        echo $code;
        exit;
    }

}