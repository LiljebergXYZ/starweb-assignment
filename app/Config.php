<?php

namespace App;

/**
 * Application configuration
 */
class Config
{

    /**
     * Database host
     *
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     *
     * @var string
     */
    const DB_NAME = 'starweb';

    /**
     * Database user
     *
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password
     *
     * @var string
     */
    const DB_PASSWORD = 'mysql';

    /**
     * Default Controller
     *
     * @var string
     */
    const DEFAULT_CONTROLLER = 'Index';

    /**
     * Array defining all paths
     *
     * @var array
     */
    const PATHS = array(
        'app' => 'app',
        'controller' => 'controllers',
        'model' => 'models',
        'view' => 'views'
    );
}