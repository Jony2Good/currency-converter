<?php

namespace App\System\Route;

class RouteDispatcher
{
    /**
     * @var string
     */
    private string $requestUri = '/';

    /**
     * @var array<string>
     */
    private array $paramMap = [];
    /**
     * @var array<string>
     */
    private array $paramRequestMap = [];
    /**
     * @var RouteConfiguration
     */
    private RouteConfiguration $routeConfiguration;

    /**
     * @param RouteConfiguration $routeConfiguration
     */
    public function __construct(RouteConfiguration $routeConfiguration)
    {

        $this->routeConfiguration = $routeConfiguration;
    }

    /**
     * @return void
     */
    public function process(): void
    {
        $this->saveRequestUri();
        $this->setParamMap();
        $this->makeRegexRequest();
        $this->run();
    }

    /**
     * @return void
     */
    private function saveRequestUri(): void
    {
        if ($_SERVER['REQUEST_URI'] !== '/') {
            $this->requestUri = $this->clean($_SERVER['REQUEST_URI']);
            $this->routeConfiguration->route = $this->clean($this->routeConfiguration->route);
        }
    }

    /**
     * @param string $str
     * @return string
     */
    private function clean(string $str): string
    {
        return preg_replace('/(^\/)|(\/$)/', '', $str);
    }

    /**
     * @return void
     */
    private function setParamMap(): void
    {
        $routeArray = explode('/', $this->routeConfiguration->route); //строка, указанная в команде Route файла web.php, разбитая на массив ключей и параметров

        foreach ($routeArray as $key => $value) {
            if (preg_match('/{.*}/', $value)) {
                $this->paramMap[$key] = preg_replace('/(^{)|(}$)/', "", $value); //в массив приходят данные взяте из скобок: 1.ключ - позиция значения параметра в массиве 2. сам параметр
            }
        }
    }

    /**
     * @return void
     */
    private function makeRegexRequest(): void
    {
        $requestUriArray = explode('/', $this->requestUri);

        foreach ($this->paramMap as $key => $value) {
            if (!isset($requestUriArray[$key])) {
                return;
            } else {
                $this->paramRequestMap[$value] = $requestUriArray[$key];
                $requestUriArray[$key] = '{.*}';
            }
        }

        $this->requestUri = implode('/', $requestUriArray);
        $this->prepareRegexRequest();
    }

    /**
     * @return void
     */
    private function prepareRegexRequest(): void
    {
        $this->requestUri = str_replace('/', '\/', $this->requestUri);
    }

    /**
     * @return void
     */
    private function run(): void
    {
        if (preg_match("/$this->requestUri/", $this->routeConfiguration->route)) {
            $this->render();
        }
    }

    /**
     * @return void
     */
    private function render(): void
    {
        $class = $this->routeConfiguration->controller;
        $method = $this->routeConfiguration->action;
        print_r((new $class())->$method(...array_values($this->paramRequestMap)));
        die();
    }


}
