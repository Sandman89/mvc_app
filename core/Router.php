<?php

namespace core;
use core\View;
use Exception;

class Router
{
    protected $routes = [];
    protected $params = [];
    private $url;
    private $matchUrl;

    public function __construct() {
        $arr = require 'application/config/routes.php';
        foreach ($arr as $key => $val) {
            $this->add($key, $val);
        }
        $this->url =  ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $this->matchUrl =  trim(parse_url($this->url, PHP_URL_PATH), '/');

    }

    public function add($route, $params) {
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }


    public function match() {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route,  $this->matchUrl, $matches)) {
                $this->params = $params;

                return true;
            }
        }
        return false;
    }

    public function run(){
        if ($this->match()) {
          //  debug($this->routes);
            $this->SetParams();
            $path = 'application\controllers\\'.ucfirst($this->params['controller']).'Controller';
            if (class_exists($path)) {
                $action = 'action'.ucfirst($this->params['action']);
                if (method_exists($path, $action)) {
                    $controller = new $path($this->params);
                    $response = $controller->$action();
                    echo $response;
                } else {
                    throw new Exception(sprintf('This method not found'));

                }
            } else {
                throw new Exception(sprintf('This path controller not found'));
            }
        } else {
            throw new Exception(sprintf('This route not registered'));
        }
    }

    private function SetParams(){
        $this->params['url'] = $this->url;
        $this->params['matchUrl'] = $this->matchUrl;
        $urlQuery = parse_url($this->url, PHP_URL_QUERY);
        $queryParams = [];
        if (!empty($urlQuery)){
            parse_str( parse_url($this->url, PHP_URL_QUERY),  $queryParams);
        }
        $this->params['queryParams'] = $queryParams;

    }
}