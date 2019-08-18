<?php

return array(
    'virtual_paths' => array(
        3 => array(
            '/^[\x7f-\xff]+$/' => ROOT_DIR . '/tmp/www/test.php',
        ),
        7 => array(
            '/[a-z]{7,7}/' => ROOT_DIR . '/tmp/www/test.php',
        ),
    ),
);
