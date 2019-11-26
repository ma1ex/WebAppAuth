<?php

/**
 * Project: auth.local;
 * File: env.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 24.11.2019, 22:39
 * Comment: Environment config
 */

// Application directory names
$names = [
    'application' => 'app', // Name of the directory with Application
    'models' => 'Models', // Name of the directory with Models
    'controllers' => 'Controllers', // Name of the directory with Controllers
    'views' => 'Views', // Name of the directory with Views
    'layouts' => 'layouts', // Name of the directory with Views/layouts
    'errors' => 'errors', // Name of the directory with Views/errors
    'libs' => 'libs', // Name of the directory with third-party libraries
    // Namespace
    'namespaceModels' => 'app\Models\\',
];

/* =============================================================================
 * =============================================================================
 * =============================================================================
 */

// Abbreviated
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

// Example: http://this-site-domain.com/
if (!defined('APP_HTTP_PATH')) {
    define('APP_HTTP_PATH', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/');
}

// Application base path
if (!defined('APP_BASE_PATH')) {
    define('APP_BASE_PATH', $names['application']);
}

// Models directory
if (!defined('APP_MODELS_PATH')) {
    define('APP_MODELS_PATH', dirname(__DIR__) . DS . $names['models'] . DS);
}
// Models namespace
if (!defined('APP_MODELS_NAMESPACE')) {
    define('APP_MODELS_NAMESPACE', $names['namespaceModels']);
}

// View directory
if (!defined('APP_TPL_PATH')) {
    define('APP_TPL_PATH', dirname(__DIR__) . DS . $names['views'] . DS);
}

// Errors view directory
if (!defined('APP_TPL_ERRORS_PATH')) {
    define('APP_TPL_ERRORS_PATH', APP_TPL_PATH . $names['errors'] . DS);
}

// Layouts view directory
if (!defined('APP_TPL_LAYOUTS_PATH')) {
    define('APP_TPL_LAYOUTS_PATH', APP_TPL_PATH . $names['layouts'] . DS);
}