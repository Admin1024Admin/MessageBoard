<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2018/9/10
 * Time: 11:24
 */
    return [
        // 应用调试模式
        'app_debug' => true,
        //开启路由
        'url_route_on'=> true,
        'URL_HTML_SUFFIX' => '',
        //配置session
        'session' => [ 'prefix' => 'think', 'type' => '', 'auto_start' => true, ],
        // 视图输出字符串内容替换
        'view_replace_str'       => [
            '__STATIC__' => '/static',
            '__CSS__'    => '/static/css',
            '__JS__'     => '/static/js',
            '__IMG__'    =>  '/static/images',
            '__ICON__'    => '/favicon.ico',
            '__BCSS__' =>  '/static/bootstrap/css',
            '__BJS__'  =>  '/static/bootstrap/js'

        ],
        'paginate' => [
            'type' => 'bootstrap',
            'var_page' => 'page',
            'path'=>''
        ],
    ]
?>