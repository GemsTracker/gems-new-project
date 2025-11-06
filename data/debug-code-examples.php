<?php

// Example code for simple copy paste

/**
 * For line 15 in config/container.php
 * @var array $dependencies
 */
file_put_contents('data/logs/services.txt', print_r($dependencies, true) . "\n");

ini_set('error_log', 'data/logs/php_errors.log');

if ($_POST) {
    file_put_contents('data/logs/echo.log', print_r($_POST, true) . "\n", FILE_APPEND);
}

file_put_contents('data/logs/echo.txt', __CLASS__ . '->' . __FUNCTION__ . '(' . __LINE__ . '): ' .  get_class($this) . "\n", FILE_APPEND);
file_put_contents('data/logs/echo.txt', __CLASS__ . '->' . __FUNCTION__ . '(' . __LINE__ . '): ' .  print_r($this, true) . "\n", FILE_APPEND);
file_put_contents('data/logs/echo.txt', __CLASS__ . '->' . __FUNCTION__ . '(' . __LINE__ . '): ' .  print_r(array_keys($this), true) . "\n", FILE_APPEND);
file_put_contents('data/logs/echo.txt', __CLASS__ . '->' . __FUNCTION__ . '(' . __LINE__ . '): ' .  var_export($this, true) . "\n", FILE_APPEND);
file_put_contents('data/logs/echo.txt', __CLASS__ . '->' . __FUNCTION__ . '(' . __LINE__ . '): ' .  print_r(array_keys($request->getAttributes()), true) . "\n", FILE_APPEND);
