<?php

namespace App\System\Route;

class RouteDispatcher
{
    private string $requestUri = '/';

    private array $paramMap = [];
    private array $paramRequestMap = [];
    private RouteConfiguration $routeConfiguration;

    /**
     * @param RouteConfiguration $routeConfiguration
     */
    public function __construct(RouteConfiguration $routeConfiguration)
    {

       $this->routeConfiguration = $routeConfiguration;
    }

    public function process(): void
    {
        $this->saveRequestUri();
        $this->setParamMap();
        $this->makeRegexRequest();
        $this->run();
    }

    private function saveRequestUri(): void
    {
        if ($_SERVER['REQUEST_URI'] !== '/') {
            $this->requestUri = $this->clean($_SERVER['REQUEST_URI']);
            $this->routeConfiguration->route = $this->clean($this->routeConfiguration->route);
        }
    }

    private function clean($str): string
    {
        return preg_replace('/(^\/)|(\/$)/', '', $str);
    }

    private function setParamMap(): void
    {
        $routeArray = explode('/', $this->routeConfiguration->route); //строка, указанная в команде Route файла web.php, разбитая на массив ключей и параметров

        foreach ($routeArray as $key => $value) {
            if (preg_match('/{.*}/', $value)) {
                $this->paramMap[$key] = preg_replace('/(^{)|(}$)/', "", $value); //в массив приходят данные взяте из скобок: 1.ключ - позиция значения параметра в массиве 2. сам параметр
            }
        }
    }

    private function makeRegexRequest(): void
    {
        $requestUriArray = explode('/', $this->requestUri); //текущий URI, который разбивается на массив

        foreach ($this->paramMap as $key => $value) { //если в массиве с текущим URI есть значение с
            if (!isset($requestUriArray[$key])) { // ключом из массива с параметрами
                return;
            } else {
                $this->paramRequestMap[$value] = $requestUriArray[$key];
                $requestUriArray[$key] = '{.*}';//переделываем в регулярное выражение
            }
        }

        $this->requestUri = implode('/', $requestUriArray); //текущий URI cсбирается в строку
        $this->prepareRegexRequest();
    }

    private function prepareRegexRequest(): void
    {
        $this->requestUri = str_replace('/', '\/', $this->requestUri);
    }

    private function run(): void
    {
        if (preg_match("/$this->requestUri/", $this->routeConfiguration->route)) {
            $this->render();
        }
    }

    private function render(): void
    {
        $class = $this->routeConfiguration->controller;
        $method = $this->routeConfiguration->action;
        print_r((new $class)->$method(...array_values($this->paramRequestMap)));
        die();
    }


}