<?php

$path = $_SERVER['REQUEST_URI'];

if ($path === '/ready') {
    return;
}

if ($path === '/oauth/token.php') {
    require __DIR__ . '/token.php';
} elseif ($path === '/action/test') {
    require __DIR__ . '/action.php';
} else {
    header("HTTP/1.1 404 Not Found");
}
