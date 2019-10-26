<?php

namespace Baldr\APIDiscovery\Utils;

class RouteMatcher {
    private $router;

    public function __construct($router)
    {
        $this->router = $router;
    }

    public function getAllRoutes(){
        $routes = $this->router->getRoutes();
        return $routes;
    }
}