<?php
namespace App\System\Route;

class Route
{
    private static array $routesPost = [];

       /**
     * @return array
     */
    public static function getRoutesPost(): array
    {
        return self::$routesPost;
    }

    /**
     * @param string $route
     * @param array $controller
     * @return RouteConfiguration
     */
    public static function post(string $route, array $controller): RouteConfiguration
    {
        return self::register($route, $controller, self::$routesPost);
    }

    /**
     * @param string $route
     * @param array $controller
     * @param $arrayRoutes
     * @return RouteConfiguration
     */
    private static function register(string $route, array $controller, &$arrayRoutes): RouteConfiguration
    {
        $routeConfiguration = new RouteConfiguration($route, $controller[0], $controller[1]);
        $arrayRoutes[] = $routeConfiguration;
        return $routeConfiguration;
    }
}