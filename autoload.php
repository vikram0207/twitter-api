<?php
/**
 * Autoload
 *
 * 
 * @package  Twitter-API
 * @param string $class The fully-qualified class name.
 * @link https://github.com/vikram0207/twitter-api
 * @return void
 */
spl_autoload_register(function ($class) {


    // base directory for the namespace prefix
    $base_dir = __DIR__ . DIRECTORY_SEPARATOR . 'lib' ;

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . DIRECTORY_SEPARATOR . $class. '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
