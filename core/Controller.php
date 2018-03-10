<?php

namespace Nano;

use App\Config;

/**
 * Base controller
 */
abstract class Controller {

    /**
     * Default layout file
     *
     * @var string
     */
    protected $_layout = 'layout.php';

    /**
     * Class name
     *
     * @var string
     */
    protected $_class;

    /**
     * Constructor
     */
    public function __construct() {
        $_class = get_class($this);
    }

    /**
     * Renders the controller based on supplied view
     * and arguments
     *
     * @param string $view
     * @param array $args
     * @return void
     */
    public function render($view, $args = array()) {
        if(!method_exists($this, $view)) {
            throw new \Exception("View '{$view}' not found in {$_class}");
        }

        ob_start();
        call_user_func_array(array($this, $view), $args);

        $content = ob_get_contents();
        ob_end_clean();

        $layout = ABSPATH . '/' . Config::PATHS['app'] . '/' . Config::PATHS['view'] . '/' . $this->_layout;
        if(file_exists($layout)) {
            include($layout);
        } else {
            echo $content;
        }
    }

}