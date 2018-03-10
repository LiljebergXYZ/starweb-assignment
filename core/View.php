<?php

namespace Nano;

use App\Config;

/**
 * View class
 */
class View {

    /**
     * Render the view with arguments
     *
     * @param string $view
     * @param array $args
     * @return void
     */
    public static function render($view, $args = array()) {
        extract($args);
        $view_file = ABSPATH . '/' . Config::PATHS['app'] . '/' . Config::PATHS['view'] . '/' . $view . '.php';
        if(!file_exists($view_file)) {
            throw new \Exception("View {$view} does not exist");
        }
        include($view_file);
    }

}