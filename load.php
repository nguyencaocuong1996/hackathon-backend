<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/1/2017
 * Time: 10:59 PM
 */
session_start();
/*
 * If constant ABS_PATH is not defined, define it is the abstract path of file load.php
 */
if(!defined('SITE_PATH')){
    define('SITE_PATH', 'http://hackathon.dev');
}
if (! defined('ABS_PATH')) {
    define('ABS_PATH', dirname(__FILE__) . '/');
}


if (! defined('CORE_PATH')) {
    define('CORE_PATH', ABS_PATH . 'core/');
}

if (! defined('CONFIG_PATH')) {
    define('CONFIG_PATH', ABS_PATH . 'config/');
}

if(! defined('SRC_PATH') ){
    define('SRC_PATH', ABS_PATH . 'src/');
}

if (! defined('DB_PATH')) {
    define('DB_PATH', CORE_PATH . 'database/');
}

if (! defined('USER_PATH')) {
    define('USER_PATH', CORE_PATH . 'user/');
}

if (! defined('MODEL_PATH')) {
    define('MODEL_PATH', ABS_PATH . 'model/');
}
/*
 * If file db.config.php exists in root folder
 * require db.config.php
 */
if (file_exists(CONFIG_PATH . 'db.config.php')) {
    require_once( CONFIG_PATH . 'db.config.php' );
}
spl_autoload_register(function ($class) {
    $array = explode('\\', $class);
    $class = end($array);
    array_shift($array);
    $class_path = '';
    foreach ($array as $item => $value) {
        if ($value != $class) {
            $class_path .= $value . '/';
        } else {
            $class_path .= $value . '.php';
        }
    };
    if (file_exists(CORE_PATH . $class_path)) {
        require_once CORE_PATH . $class_path;
    } else {
        if (file_exists(SRC_PATH . $class_path)) {
            require_once SRC_PATH . $class_path;
        }
    }
});
/*
 * Load the database class file and instantiate the `$db` global.
 *
 * @since 1.0
 *
 */
$connect_arg = array( 'host' => DB_HOST, 'username' => DB_USER, 'password' => DB_PASSWORD, 'db' => DB_NAME, 'charset' => DB_CHARSET );
$db = new wind\database\MysqliDb($connect_arg);