<?php

return array(
    'virtual_paths' => array(
        '' => array(
            '/^[a-z\.]{7,12}$/' => 'filename',
            '/^[a-z]{4}(.*)$/' => 'filename',
        ),
        'filename' => ROOT_DIR . '/www/.dist/a-z7.php',
        3 => array(
            '/^[\x7f-\xff]+$/' => ROOT_DIR . '/www/.dist/test.php',
        ),
    ),
    'virtual_hosts' => array(
        '' => array(
            'http_host' => ROOT_DIR . '/www/.dist/http_host.php',
        ),
        'yingmi.xyz' => ROOT_DIR . '/www\work\wuding\astrology\web/index.php',
        'urlnk.cc urlnk.host' => array('http_host'),
    ),
    'host_domain' => '.loc.urlnk.com',
);
