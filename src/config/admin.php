<?php

use Ebcms\Framework\Framework;
use DigPHP\Router\Router;

return [
    'menus' => Framework::execute(function (
        Router $router
    ): array {
        $res = [];
        $res[] = [
            'title' => '信息管理',
            'tags' => ['primary'],
            'url' => $router->build('/ebcms/scms/content/index'),
        ];
        $res[] = [
            'title' => '站点管理',
            'tags' => ['secondary'],
            'url' => $router->build('/ebcms/scms/site/index'),
        ];
        $res[] = [
            'title' => '数据管理',
            'tags' => ['secondary'],
            'url' => $router->build('/ebcms/scms/source/index'),
        ];
        $res[] = [
            'title' => '模型管理',
            'tags' => ['secondary'],
            'url' => $router->build('/ebcms/scms/model/index'),
        ];
        return $res;
    }),
];
