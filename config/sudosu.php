<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Allowed Domains
    |--------------------------------------------------------------------------
    |
    | By default this contains the tld's in https://tools.ietf.org/html/rfc2606
    | If you want you can add your own domain, as well as a port if needed.
    | E.g.: localhost:8080, foobar.dev, *.foobar.dev, etc.
    |
     */

    'domains' => [
        '*.example',
        '*.localhost',
        '*.test',
    ],

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | Path to the application User model. This will be used to retrieve the users
    | displayed in the select dropdown. This must be an Eloquent Model instance.
    |
     */
    
    'user_model' => App\User::class
    
];
