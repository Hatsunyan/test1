<?php
spl_autoload_register(function ($class) //autoLoad
{
    $file =  __DIR__.'/app/classes/' . mb_strtolower($class).'.php';
    if(file_exists($file))
    {
        require_once $file;
    }
});
require_once 'app/start.php';
Route::start();
