<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

if (Config::get('database.default') === 'sqlite') {
    $path = Config::get('database.connections.sqlite.database');
    if (!file_exists($path) && is_dir(dirname($path))) {
        touch($path);
    }
}
