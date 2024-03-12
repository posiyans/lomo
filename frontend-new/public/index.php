<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));
/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/
$demo = env('DEMO', false);
if ($demo && $_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
    session_start();
    if (!isset($_SESSION['sql_lite'])) {
        $user = time();
        $_SESSION['sql_lite'] = $user;
    } else {
        $user = $_SESSION['sql_lite'];
    }
    $path = realpath(__DIR__ . '/../database/' . $user . '_database.sqlite');
    if (!file_exists($path)) {
        copy(__DIR__ . '/../database/database.sqlite', __DIR__ . '/../database/' . $user . '_database.sqlite');
    }
    putenv("DB_DATABASE=" . $path);
}
require __DIR__ . '/../vendor/autoload.php';
/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);

