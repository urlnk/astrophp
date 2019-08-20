<?php

return array(
    'virtual_paths' => array(
        '^' => 'robot.urlnk.com',
        '' => array(
            '/^[a-z\.]{7,12}$/' => 'filename',
            '/^[a-z]{4}(.*)$/' => 'filename',
            '/^[\x7f-\xff]+$/' => 'chinese', // 中文
        ),
        'filename' => ROOT_DIR . '/www/.dist/a-z7.php',
        'chinese' => ROOT_DIR . '/www/.dist/test.php',
        3 => array(
            '/^[\x7f-\xff]+$/' => ROOT_DIR . '/www/.dist/test.php',
        ),
    ),
    'virtual_hosts' => array(
        '' => array(
            'http_host' => ROOT_DIR . '/www/.dist/http_host.php',
            'astro' => ROOT_DIR .'/www\work\wuding\astrology\web\index.php',
            'mr-fact' => ROOT_DIR .'/www\work\netjoin/mr-fact\web\index.php',
        ),
        'yingmi.xyz' => ROOT_DIR . '/www\work\wuding\astrology\web/index.php',
        'urlnk.cc urlnk.host' => array('http_host'),
        'robot.urlnk.com' => array('astro'),
        'api.urlnk.com' => array('mr-fact'),
    ),
    'host_domain' => '.loc.urlnk.com',
    'example' => 'magic-cube/index',
    # 'example' => 'fast-route/index',
    'uri_custom' => '',
    # 'uri_custom' => 'get,post:users!wubenli@documents$1%2Fview', //$1
);
