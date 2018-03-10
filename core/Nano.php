<?php

use App\Config;

require(__DIR__ . '/Database.php');
require(__DIR__ . '/Model.php');
require(__DIR__ . '/View.php');
require(__DIR__ . '/Controller.php');

/**
 * An autoload function that loads both controllers and models
 */
spl_autoload_register(function ($class_name) {
    foreach(Config::PATHS as $key => $path) {
        if($key == 'app') { continue; }
        // Set base path
        $base_path = ABSPATH . '/' . Config::PATHS['app'] . '/' . $path . '/';

        // Check if 'app\' is in class_name
        $app_pos = stripos($class_name, 'app\\');
        if($app_pos === 0) {
            $class_name = substr($class_name, strlen('app\\'));
        }

        // Try the default setup for a controller
        $class_path = $base_path . $class_name . '.php';

        //Check if the path exists before including it
        if(file_exists($class_path)) {
            include($class_path);
            return;
        }

        // Try with ucfirst
        $class_path = $base_path . ucfirst($class_name) . '.php';
        if(file_exists($class_path)) {
            include($class_path);
            return;
        }

        // Maybe it exists with all lower
        $class_path = $base_path . strtolower($class_name) . '.php';
        if(file_exists($class_path)) {
            include($class_path);
            return;
        }
    }
    //throw new \Exception(substr($class_path, strlen($base_path)));
});

/**
 * Initiates the Nano framework
 *
 * @return void
 */
function nano_init() {
    // Get the URI
    $uri = $_SERVER['REQUEST_URI'];
    $actionPrefix = 'action';

    //Set default controller and action
    $class = Config::DEFAULT_CONTROLLER;
    $action = 'Index';
    $args = array();

    // Split the URI into parts
    $parts = array();
    $token = strtok($uri, '/');
    while($token !== false) {
        $parts[] = $token;
        $token = strtok('/');
    }

    // In the case of Nano we assume that there are no modules
    if(count($parts) > 1) {
        // If the path is a method in the default controller
        // Use it primarily
        $action = ucfirst($parts[0]);
        if(method_exists('App\\' . Config::DEFAULT_CONTROLLER, $actionPrefix . $action)) {
            unset($parts[0]);
            foreach($parts as $part) {
                if(is_numeric($part) && !isset($args['id'])) {
                    $args['id'] = $part;
                }
                $args[] = $part;
            }
        } else {
            $class = $parts[0];
            $action = ucfirst($parts[1]);
        }
    } else if(count($parts) == 1) {
        $action = $parts[0];
    }

    // Class should load from app namespace in the case of Nano we assume ucfirst
    $class = 'App\\' . ucfirst($class);
    $action = $actionPrefix . ucfirst($action);
    if(class_exists($class)) {
        $controller = new $class;
    }
    if(method_exists($controller, $action)) {
        $controller->render($action, $args);
        return;
    }
    $class = 'App\\' . Config::DEFAULT_CONTROLLER;
    $controller = new $class;
    $controller->render($actionPrefix . 'Missing', array('uri' => $uri));
}

