<?php
define("ROOT", __DIR__);

require_once('src/Application.php');

Etu\Application::autoloadRegister('\\', ROOT);

echo 'Hello, world';
