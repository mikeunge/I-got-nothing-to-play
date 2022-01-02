<?php

use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security) {
    $security->firewall('main')
        ->rememberMe()
            ->secret('%kernel.secret%')
            ->lifetime(604800) // 1 week in seconds
            ->path('/')
            ->alwaysRememberMe(true)
    ;
};

?>