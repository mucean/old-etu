<?php
namespace Etu\Tests;
defined('DEBUG') || define('DEBUG', true);
include(__DIR__."/../src/Application.php");

\Etu\Application::autoloadRegister('Etu', __DIR__.'/../src');
