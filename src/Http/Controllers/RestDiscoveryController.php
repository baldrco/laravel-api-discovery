<?php
namespace Baldr\APIDiscovery\Http\Controllers;

use Baldr\APIDiscovery\Utils\RouteMatcher;

class RestDiscoveryController {

    public function __invoke()
    {
        $retn = ["resources"=>[]];
        $routeMatcher = new RouteMatcher(app('router'));

        // We get all the routes
        $routes = $routeMatcher->getAllRoutes();

        // For each route, we fetch validation rules and inject in return array
        foreach($routes as $route){
            $routeName = $route->getName();

            // We only process named routes
            if(is_null($routeName)) {
                continue;
            }

            // If route does not match category_name we skip
            if(preg_match('/([A-z]){0,}\_([A-z]){0,}/', $routeName) === false){
                continue;
            }

            // We process data to return in correct format
            $name = explode('_', $routeName);
            if(count($name) !== 2){
                continue;
            }

            $category = $name[0];
            $name = $name[1];
            $uri = $route->uri();
            $method = implode('|', array_diff($route->methods(), ['HEAD']));

            // We extract URI params
            $params = [];
            $params_final = [];
            preg_match('/\{([A-z]{0,})\}/', $uri, $params);
            foreach($params as $param){
                if($param[0] == '{' && $param[strlen($param) - 1] == '}'){
                    continue;
                }
                $params_final[$param] = 'string';
            }

            $validators = [];

            // If array index of category name does not exists
            if(!isset($retn['resources'][$category])){
                $retn['resources'][$category] = [];
            }

            // Generate resource object and add to the payload
            $resource = new \StdClass();
            $resource->category = $category;
            $resource->name = $name;
            $resource->method = $method;
            $resource->uri = $uri;
            $resource->uri_params = $params_final;
            $resource->validation_rules = $validators;
            $retn['resources'][$category][$name] = $resource;
        }
        return json_encode($retn);
    }
}