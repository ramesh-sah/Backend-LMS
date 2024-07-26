<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web', // Set default guard to 'web' for session-based auth
        'passwords' => 'users', // Assuming you have a separate password reset for regular users
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'admins', // Use the 'users' provider for web auth
        ],

        'admin' => [ // Add a separate guard for admin users
            'driver' => 'sanctum',
            'provider' => 'admins',
        ],

        'member' => [ // Add a separate guard for employee
            'driver' => 'sanctum',
            'provider' => 'members',
        ],
        'employee' => [ // Add a separate guard for admin users
            'driver' => 'sanctum',
            'provider' => 'employees',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        // 'users' => [ // Provider for regular users
        //     'driver' => 'eloquent',
        //     'model' => App\Models\User::class, // Assuming you have a User model for regular users
        // ],
        'admins' => [ // Provider for admin users
            'driver' => 'eloquent',
            'model' => App\Http\Controllers\Api\AdminUser\Model\Admin::class, // Assuming you have an Admin model for admin users
        ],
        'members' => [ // Provider for admin users
            'driver' => 'eloquent',
            'model' => App\Http\Controllers\Api\Member\Model\Member::class, // Assuming you have an Admin model for admin users
        ],
        'employees' => [ // Provider for admin users
            'driver' => 'eloquent',
            'model' => App\Http\Controllers\Api\Employee\Model\Employee::class, // Assuming you have an Admin model for admin users
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [ // Password reset for regular users
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admin' => [ // Password reset for admin users
            'provider' => 'admins',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'member' => [ // Password reset for admin users
            'provider' => 'members',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'employee' => [ // Password reset for admin users
            'provider' => 'employees',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

];
