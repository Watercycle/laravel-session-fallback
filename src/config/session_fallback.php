<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Session Driver Fallback Order
    |--------------------------------------------------------------------------
    |
    | Here you may list all the possible session drivers you want to use,
    | in the order you want to fall back to them on. The package will go
    | down the list until it finds a working session driver.
    |
    */

    'fallback_order'=>[
        'redis',
        'memcached',
        'database',
        'cookie',
        'file',
        'array'
    ],

    /*
    |--------------------------------------------------------------------------
    | Attempts Before Fallback
    |--------------------------------------------------------------------------
    |
    | In some cases, such as connection timeout errors, retrying whatever
    | operation we performed (e.g. trying to instantiate a driver) will
    | get rid of whatever exception came up initially. This setting can be
    | configured to the number of attempts that should be made before a
    | fallback occurs.
    |
    */
    'attempts_before_fallback' => 1,

    /*
    |--------------------------------------------------------------------------
    | Interval Between Attempts
    |--------------------------------------------------------------------------
    |
    | This defines, in milliseconds, how long we should wait before
    | performing our retries. See the previous configuration comment for
    | why we may want this.
    |
    */
    'interval_between_attempts' => 20,
];
